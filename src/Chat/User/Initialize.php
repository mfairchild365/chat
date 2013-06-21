<?php
namespace Chat\User;

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
                $event->addRoute('/^users\/(?P<id>[\d]+)\/edit$/i', __NAMESPACE__ . '\Edit');
                $event->addRoute('/^register$/i', __NAMESPACE__ . '\Register');
                $event->addRoute('/^logout$/i', __NAMESPACE__ . '\Logout');
                $event->addRoute('/^login$/i', __NAMESPACE__ . '\Login');
                $event->addRoute('/^users\/(?P<id>[\d]+)$/i', __NAMESPACE__ . '\View');
            }
        );

        $listeners[] = array(
            'event'    => \Chat\Events\NavigationSubCompile::EVENT_NAME,
            'listener' => function (\Chat\Events\NavigationSubCompile $event) {
                //Try to parse the user ID out of the current url.
                if (!preg_match('/users\/(\d+)/', \Chat\Util::getCurrentURL(), $matches)) {
                    return;
                }

                $userID = $matches[1];

                $event->addNavigationItem(\Chat\Config::get('URL') . 'users/' . $userID, 'Profile');

                //Only add the edit link if we have access to edit.
                if (!$user = Service::getCurrentUser()) {
                   return;
                }

                if ($user->id != $userID && $user->role == 'ADMIN') {
                    return;
                }

                $event->addNavigationItem(\Chat\Config::get('URL') . 'users/' . $userID . '/edit', 'Edit');
            }
        );

        return $listeners;
    }
}