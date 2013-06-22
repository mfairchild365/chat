<?php
namespace Chat\WebSocket\Events;

class OnMessage extends \Symfony\Component\EventDispatcher\Event
{
    const EVENT_NAME = 'websocket.onmessage';

    protected $connection;
    protected $action;
    protected $data;

    public function __construct(\Chat\WebSocket\ConnectionContainer $connection, $action, $data)
    {
        $this->connection = $connection;
        $this->action = $action;
        $this->data = $data;
    }

    /**
     * @return \Chat\WebSocket\ConnectionContainer
     */
    public function getConnection()
    {
        return $this->connection;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getData()
    {
        return $this->data;
    }
}