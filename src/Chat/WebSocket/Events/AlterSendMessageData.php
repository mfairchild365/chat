<?php
namespace Chat\WebSocket\Events;

class AlterSendMessageData extends \Symfony\Component\EventDispatcher\Event
{
    const EVENT_NAME = 'websocket.sendmessage.alter';

    protected $connection;
    protected $data;

    public function __construct(\Chat\WebSocket\ConnectionContainer $connection, $data)
    {
        $this->connection = $connection;
        $this->data = $data;
    }

    /**
     * @return \Chat\WebSocket\ConnectionContainer
     */
    public function getConnection()
    {
        return $this->connection;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }
}