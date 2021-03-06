<?php
namespace Chat\WebSocket;

class ConnectionContainer
{
    /**
     * @var bool|\Wrench\Connection $connection
     */
    protected $connection = false;

    function __construct(\Ratchet\ConnectionInterface $connection)
    {
        $this->setConnection($connection);
    }

    /**
     * @return bool|\Wrench\Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @return bool|\Chat\User\User
     */
    public function getUser()
    {
        return \Chat\User\User::getByID($this->connection->Session->get('user.id'));
    }

    /**
     * @param \Ratchet\ConnectionInterface $connection
     */
    public function setConnection(\Ratchet\ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param $action
     * @param $data
     */
    public function send($action, $data)
    {
        Application::sendMessageToClient($this->getConnection(), $action, $data);
    }

    function __call($name, $arguments)
    {
        if (method_exists($this->connection, $name)) {
            call_user_func_array(array($this->connection, $name), $arguments);
        }
    }
}