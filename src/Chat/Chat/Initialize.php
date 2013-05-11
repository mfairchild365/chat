<?php
namespace Chat\Chat;

class Initialize implements \Chat\InitializePluginInterface
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
            'event'    => \Chat\Events\CompileRoutes::EVENT_NAME,
            'listener' => function (\Chat\Events\CompileRoutes $event) {
                $event->addRoute('/^$/', __NAMESPACE__ . '\View');
            }
        );

        return $listeners;
    }
}