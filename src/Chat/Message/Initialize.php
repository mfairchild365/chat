<?php
namespace Chat\Message;

class Initialize implements \Chat\Plugin\InitializePluginInterface
{
    public function __construct(array $options)
    {

    }

    public function initialize()
    {

    }

    public function getEventListeners()
    {
        $listeners = array();

        $listeners[] = array(
            'event'    => \Chat\WebSocket\Events\OnOpen::EVENT_NAME,
            'listener' => function (\Chat\WebSocket\Events\OnOpen $event) {
                echo 'here' . PHP_EOL;
                //Get the user up to date on the conversation
                foreach (\Chat\Message\RecordList::getAllMessages() as $message) {
                    $event->getConnection()->send('MESSAGE_NEW', $message);
                }
            }
        );

        return $listeners;
    }
}