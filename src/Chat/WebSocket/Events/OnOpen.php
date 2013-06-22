<?php
namespace Chat\WebSocket\Events;

class OnOpen extends \Symfony\Component\EventDispatcher\Event
{
    const EVENT_NAME = 'websocket.onopen';

    protected $connection;

    public function __construct(\Chat\WebSocket\ConnectionContainer $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return \Chat\WebSocket\ConnectionContainer
     */
    public function getConnection()
    {
        return $this->connection;
    }
}