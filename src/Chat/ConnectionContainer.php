<?php
namespace Chat;

class ConnectionContainer
{
    protected $connection = false; // \Wrench\Connection object
    protected $mac        = false; // Cash the MAC address as arp lookup may be slow.

    function __construct(\Ratchet\ConnectionInterface $connection)
    {
        $this->setConnection($connection);
        $this->mac = \LAN\Util::getMac($connection->remoteAddress);
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function getUser()
    {
        return User\Record::getUser($this->connection, $this->mac);
    }

    public function setConnection(\Ratchet\ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }


    public function send($action, $data)
    {
        Application::sendMessageToClient($this->getConnection(), $action, $data);
    }
}