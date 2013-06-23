<?php
namespace Chat\WebSocket\Events;

class AddPeriodicTimer extends \Symfony\Component\EventDispatcher\Event
{
    const EVENT_NAME = 'websocket.addperiodictimer';

    protected $timers = array();

    public function __construct()
    {

    }

    public function getTimers()
    {
        return $this->timers;
    }

    public function addTimer($interval, callable $callback)
    {
        $this->timers[] = array(
            'interval' => $interval,
            'callback' => $callback
        );
    }
}