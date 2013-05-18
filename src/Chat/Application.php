<?php
namespace Chat;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Application implements MessageComponentInterface {
    public static $connections = array();

    public function onOpen(ConnectionInterface $connection) {
        //Save in array
        self::$connections[$connection->resourceId] = new ConnectionContainer($connection);

        //Set as online.
        if (!$user = self::$connections[$connection->resourceId]->getUser()) {
            self::$connections[$connection->resourceId]->send('ERROR_AUTH', array('message'=>'You must log in'));
            $connection->close();
            return;
        }

        $user->chat_status = "ONLINE";
        $user->save();

        //Display connection on server.
        echo "--------NEW CONNECTION--------" . PHP_EOL;
        echo "Resource ID  : " . self::$connections[$connection->resourceId]->getConnection()->resourceId . PHP_EOL;
        echo "Users.id : " .  self::$connections[$connection->resourceId]->getUser()->id . PHP_EOL;

        //Update the client's list with all users currently online.
        foreach (User\RecordList::getAll() as $data) {
            self::$connections[$connection->resourceId]->send('USER_CONNECTED', $data);
        }

        //Send the client information about the logged in user
        self::$connections[$connection->resourceId]->send('USER_INFORMATION', $user);

        //Tell everyone else that this guy just came online.
        if ($this->getUserConnectionCount($user->id) == 1) {
            self::sendToAll("USER_CONNECTED", $user);
        }

        //Get the user up to date on the conversation
        foreach (Message\RecordList::getAllMessages() as $message) {
            self::$connections[$connection->resourceId]->send('MESSAGE_NEW', $message);
        }
    }

    public function onMessage(ConnectionInterface $connection, $msg) {
        $user = self::$connections[$connection->resourceId]->getUser();
        
        echo "--------ACTION--------" . PHP_EOL;
        echo "ID  : " . $user->id . PHP_EOL;

        $data = json_decode($msg, true);

        if (!isset($data['action'])) {
            throw new Exception("An action must be passed.");
        }

        $class = '';

        switch ($data['action']) {
            case 'UPDATE_USER':
                $class = '\Chat\User\ActionHandler';
                break;
            case 'SEND_CHAT_MESSAGE':
                $class = '\Chat\Message\ActionHandler';
                break;
            default:
                throw new Exception("Unknown action submitted by client", 400);
        }

        $handler = new $class;

        $result = $handler->handle($data['action'], $data['data'], self::$connections[$connection->resourceId]);

        if ($result) {
            self::sendToAll($result['action'], $result['data']);
        }
    }

    public function onClose(ConnectionInterface $connection) {
        $user = self::$connections[$connection->resourceId]->getUser();
        
        echo "--------CONNECTION CLOSED--------" . PHP_EOL;
        
        //May not be a set connection if an error happened during connection.
        if (isset(self::$connections[$connection->resourceId]) && $user) {
            echo "ID  : " . $user->id . PHP_EOL;

            if ($this->getUserConnectionCount($user->id) == 1) {
                self::sendToAll("USER_DISCONNECTED", $user);
            }

            //Set as offline
            $user->chat_status = "OFFLINE";
            $user->save();
        }

        $connection = self::$connections[$connection->resourceId];

        // The connection is closed, remove it, as we can no longer send it messages
        unset(self::$connections[$connection->getConnection()->resourceId]);

        if ($user) {
            self::sendToAll("USER_DISCONNECTED", $user);
        }
    }

    public function onError(ConnectionInterface $connection, \Exception $e) {
        $user = self::$connections[$connection->resourceId]->getUser();
        
        echo "--------ERROR--------" . PHP_EOL;

        //May not be a set connection if an error happened during connection.
        if (isset(self::$connections[$connection->resourceId])) {
            echo "ID  : " . $user->id . PHP_EOL;
        }

        if ($e instanceof \Chat\Renderable) {
            self::sendMessageToClient($connection, "ERROR", $e);
        }

        echo "error: " . $e->getMessage() . PHP_EOL;

        $connection->close();
    }

    public static function sendToAll($action, $data)
    {
        foreach (self::$connections as $connection) {
            $connection->send($action, $data);
        }
    }

    public function getUserConnectionCount($userID)
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

        //Render the data if we can.
        if ($data instanceof \Chat\Renderable) {
            $newData                   = array();
            $newData[get_class($data)] = $data->render();

            $data = $newData;
        }

        $message['data'] = $data;

        $connection->send(json_encode($message));
    }
}