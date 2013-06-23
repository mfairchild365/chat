<?php
namespace Chat\WebSocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Application implements MessageComponentInterface {
    public static $connections = array();

    public function onOpen(ConnectionInterface $connection) {
        //Save in array
        self::$connections[$connection->resourceId] = new ConnectionContainer($connection);

        \Chat\Plugin\PluginManager::getManager()->dispatchEvent(
            \Chat\WebSocket\Events\OnOpen::EVENT_NAME,
            new \Chat\WebSocket\Events\OnOpen(self::$connections[$connection->resourceId])
        );
    }

    public function onMessage(ConnectionInterface $connection, $msg) {
        $data = json_decode($msg, true);

        if (!isset($data['action'])) {
            throw new Exception("An action must be passed.");
        }

        \Chat\Plugin\PluginManager::getManager()->dispatchEvent(
            \Chat\WebSocket\Events\OnMessage::EVENT_NAME,
            new \Chat\WebSocket\Events\OnMessage(
                self::$connections[$connection->resourceId],
                $data['action'],
                $data['data']
            )
        );
    }

    public function onClose(ConnectionInterface $connection) {
        \Chat\Plugin\PluginManager::getManager()->dispatchEvent(
            \Chat\WebSocket\Events\OnClose::EVENT_NAME,
            new \Chat\WebSocket\Events\OnClose(self::$connections[$connection->resourceId])
        );

        $connection = self::$connections[$connection->resourceId];

        // The connection is closed, remove it, as we can no longer send it messages
        unset(self::$connections[$connection->getConnection()->resourceId]);
    }

    public function onError(ConnectionInterface $connection, \Exception $e) {
        echo "--------ERROR--------" . PHP_EOL;

        echo "error: " . $e->getMessage() . PHP_EOL;

        \Chat\Plugin\PluginManager::getManager()->dispatchEvent(
            \Chat\WebSocket\Events\OnError::EVENT_NAME,
            new \Chat\WebSocket\Events\OnError(self::$connections[$connection->resourceId], $e)
        );

        $connection->close();
    }

    public static function sendToAll($action, $data)
    {
        foreach (self::$connections as $connection) {
            $connection->send($action, $data);
        }
    }

    public static function getUserConnectionCount($userID)
    {
        $count = 0;

        foreach (self::$connections as $connection) {
            if ($connection->getUser()->id == $userID) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Sends a message to the client in JSON form.
     *
     * Possible actions include
     *   - USER_INFORMATION  - Detailed information about the logged in user (sent on onConnect)
     *   - USER_CONNECTED    - Sent to all users when a user is connected.
     *   - USER_DISCONNECTED - Sent to all users when a user is disconnected.
     *   - USER_UPDATED      - Sent to everyone when a user has been updated
     *   - MESSAGE_NEW       - Sent to all users when a new message is received.
     *   - ERROR             - Information about an error.
     *
     * example JSON output:
     * {
     *     "action": "USER_CONNECTED",
     *     "data": {
     *         "LAN\\User\\Record": {
     *             "id": "1",
     *             "username" : "bob",
     *             "first_name": "UNKNOWN",
     *             "last_name": "UNKNOWN",
     *             "date_created": "2012-12-20 15:34:13",
     *             "date_edited": "2012-12-20 15:34:13",
     *             "chat_status": "OFFLINE",
     *         }
     *     }
     * }
     *
     *
     * @param \Ratchet\ConnectionInterface $connection
     * @param $action
     * @param $data
     *
     * @throws Exception
     *
     * @return void
     */
    public static function sendMessageToClient(\Ratchet\ConnectionInterface $connection, $action, $data)
    {
        $message = array();

        $message['action'] = $action;

        \Chat\Plugin\PluginManager::getManager()->dispatchEvent(
            \Chat\WebSocket\Events\AlterSendMessageData::EVENT_NAME,
            new \Chat\WebSocket\Events\AlterSendMessageData(self::$connections[$connection->resourceId], $data)
        );

        $message['data'] = $data;

        $connection->send(json_encode($message));
    }
}