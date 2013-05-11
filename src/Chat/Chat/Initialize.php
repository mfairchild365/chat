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
            'event'    => \Chat\Events\RoutesCompile::EVENT_NAME,
            'listener' => function (\Chat\Events\RoutesCompile $event) {
                $event->addRoute('/^$/', __NAMESPACE__ . '\View');
            }
        );

        $listeners[] = array(
            'event'    => \Chat\Events\NavigationMainCompile::EVENT_NAME,
            'listener' => function (\Chat\Events\NavigationMainCompile $event) {
                $event->addNavigationItem(\Chat\Config::get('URL'), 'Home');
            }
        );

        return $listeners;
    }
}