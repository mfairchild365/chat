<?php
namespace Chat;

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
        return User\User::getUser($this->connection, $this->mac);
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
}