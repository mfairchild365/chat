<?php
namespace Chat\WebSocket\Events;

class OnError extends \Symfony\Component\EventDispatcher\Event
{
    const EVENT_NAME = 'websocket.onerror';

    protected $connection;
    protected $exception;

    public function __construct(\Chat\WebSocket\ConnectionContainer $connection, \Exception $exception)
    {
        $this->connection = $connection;
        $this->exception = $exception;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function getException()
    {
        return $this->exception;
    }
}