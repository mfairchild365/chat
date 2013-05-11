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
            'event'    => \Chat\Events\CompileRoutes::EVENT_NAME,
            'listener' => function (\Chat\Events\CompileRoutes $event) {
                $event->addRoute('/^users\/(?P<id>[\d]+)\/edit$/i', __NAMESPACE__ . '\Edit');
                $event->addRoute('/^register$/i', __NAMESPACE__ . '\Register');
                $event->addRoute('/^logout/i', __NAMESPACE__ . '\Logout');
                $event->addRoute('/^login/i', __NAMESPACE__ . '\Login');
                $event->addRoute('/^users\/(?P<id>[\d]+)$/i', __NAMESPACE__ . '\View');
            }
        );

        return $listeners;
    }
}