<?php
namespace Chat\Setting;

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
                $event->addRoute('/^admin$/', __NAMESPACE__ . '\Edit');
            }
        );

        $listeners[] = array(
            'event'    => \Chat\Events\NavigationMainCompile::EVENT_NAME,
            'listener' => function (\Chat\Events\NavigationMainCompile $event) {
                $user = \Chat\User\Service::getCurrentUser();

                //Only add the admin navigation link if they can use it
                if ($user && $user->role == 'ADMIN') {
                    $event->addNavigationItem(\Chat\Config::get('URL') . 'admin', 'Admin');
                }
            }
        );

        return $listeners;
    }
}