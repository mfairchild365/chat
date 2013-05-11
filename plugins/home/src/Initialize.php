<?php
namespace Chat\Plugins\Home;

class Initialize
{
    public $options = array();

    public function __construct($options = array())
    {

    }

    public function initialize()
    {

    }

    public function getEventListeners()
    {
        $listeners = array();

        $listeners[] = array(
            'event'    => \Chat\Events\CompileRoutes::EVENT_NAME,
            'listener' => function (\Chat\Events\CompileRoutes $event) {
                $event->addRoute('/^home$/', 'Chat\Plugins\Home\View');
             }
        );

        return $listeners;
    }

}