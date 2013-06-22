<?php
namespace Chat\WebSocket\Events;

class OnMessage extends \Symfony\Component\EventDispatcher\Event
{
    const EVENT_NAME = 'websocket.onmessage';

    protected $connection;
    protected $message;
    protected $data;

    public function __construct(\Chat\WebSocket\ConnectionContainer $connection, $message, $data)
    {
        $this->connection = $connection;
        $this->message = $message;
        $this->data = $data;
    }

    /**
     * @return \Chat\WebSocket\ConnectionContainer
     */
    public function getConnection()
    {
        return $this->connection;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getData()
    {
        return $this->data;
    }
}