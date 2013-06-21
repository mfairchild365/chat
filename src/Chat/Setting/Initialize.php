<?php
namespace Chat\Setting;

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
            'event'    => \Chat\Events\RoutesCompile::EVENT_NAME,
            'listener' => function (\Chat\Events\RoutesCompile $event) {
                $event->addRoute('/^admin$/', __NAMESPACE__ . '\Edit');
                $event->addRoute('/^admin\/settings$/', __NAMESPACE__ . '\Edit');
            }
        );

        $listeners[] = array(
            'event'    => \Chat\Events\NavigationMainCompile::EVENT_NAME,
            'listener' => function (\Chat\Events\NavigationMainCompile $event) {
                $user = \Chat\User\Service::getCurrentUser();

                //Only add the admin navigation link if they can use it
                if ($user && $user->role == 'ADMIN') {
                    $event->addNavigationItem(\Chat\Config::get('URL') . 'admin/settings', 'Admin');
                }
            }
        );

        $listeners[] = array(
            'event'    => \Chat\Events\NavigationSubCompile::EVENT_NAME,
            'listener' => function (\Chat\Events\NavigationSubCompile $event) {
                //Check against the current URL
                if (!preg_match('/\/admin/', \Chat\Util::getCurrentURL(), $matches)) {
                    return;
                }

                $event->addNavigationItem(\Chat\Config::get('URL') . 'admin/settings', 'Settings');
            }
        );

        return $listeners;
    }
}