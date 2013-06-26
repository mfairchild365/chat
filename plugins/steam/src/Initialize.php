<?php
namespace Chat\Plugins\Steam;

class Initialize implements \Chat\Plugin\InitializePluginInterface
{
    public $options = array();

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

            'event'    => \Chat\DB\Events\Record\AlterFields::EVENT_NAME,
            'listener' => function (\Chat\DB\Events\Record\AlterFields $event) {
                $record = $event->getRecord();

                if ($record->getTable() != 'users') {
                    return;
                }

                //Add the steam_id_64 field
                $fields = $event->getFields();
                $fields['steam_id_64'] = $event->getRecord()->steam_id_64;
                $event->setFields($fields);
            }
        );

        $listeners[] = array(
            'event'    => \Chat\Events\RoutesCompile::EVENT_NAME,
            'listener' => function (\Chat\Events\RoutesCompile $event) {
                $event->addRoute('/^login\/steam$/', 'Chat\Plugins\Steam\Login');
                $event->addRoute('/^users\/(?P<users_id>[\d]+)\/edit\/steam$/i', __NAMESPACE__ . '\Edit');
            }
        );

        $listeners[] = array(
            'event'    => \Chat\Events\NavigationSubCompile::EVENT_NAME,
            'listener' => function (\Chat\Events\NavigationSubCompile $event) {

                //Only add the edit link if we have access to edit.
                if (!$user = \Chat\User\Service::getCurrentUser()) {
                   return;
                }

                //Try to parse the user ID out of the current url.
                if (!preg_match('/users\/(\d+)/', \Chat\Util::getCurrentURL(), $matches)) {
                    return;
                }

                $userID = $matches[1];

                //Make sure they have permission
                if ($user->id != $userID && $user->role == 'ADMIN') {
                    return;
                }

                $event->addNavigationItem(\Chat\Config::get('URL') . 'users/' . $userID . '/edit/steam', 'Edit Steam Info');
            }
        );

        $listeners[] = array(
            'event'    => \Chat\WebSocket\Events\AddPeriodicTimer::EVENT_NAME,
            'listener' => function (\Chat\WebSocket\Events\AddPeriodicTimer $event) {
                $event->addTimer(15, function() {
                    static $oldUsers;

                    if (!$users = Service::getCachedSteamUserInfo()) {
                        return;
                    }

                    if ($oldUsers == $users) {
                        return;
                    }

                    \Chat\WebSocket\Application::sendToAll('STEAM_USER_INFO', $users);

                    $oldUsers = $users;
                });
            }
        );

        $listeners[] = array(
            'event'    => \Chat\WebSocket\Events\OnOpen::EVENT_NAME,
            'listener' => function (\Chat\WebSocket\Events\OnOpen $event) {
                if (!$users = Service::getCachedSteamUserInfo()) {
                    return;
                }

                $event->getConnection()->send('STEAM_USER_INFO', $users);
            }
        );

        $listeners[] = array(
            'event'    => \Chat\Events\JavascriptCompile::EVENT_NAME,
            'listener' => function (\Chat\Events\JavascriptCompile $event) {
                //Add steam to every page
                $event->addScript(\Chat\Config::get('URL') . 'plugins/steam/www/templates/html/js/steam.js');
            }
        );

        return $listeners;
    }

}