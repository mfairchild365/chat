<?php
namespace Chat\User;

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
            'event'    => 'routes.compile',
            'listener' => function (\Chat\Events\CompileRoutes $event) {
                $event->addRoute('/^home$/', 'Chat\Plugins\Home\View');
            }
        );

        return $listeners;
    }
}