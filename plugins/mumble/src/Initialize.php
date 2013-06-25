<?php
namespace Chat\Plugins\Mumble;

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
                $fields['mumble_name'] = $event->getRecord()->mumble_name;
                $event->setFields($fields);
            }
        );

        $listeners[] = array(
            'event'    => \Chat\Events\RoutesCompile::EVENT_NAME,
            'listener' => function (\Chat\Events\RoutesCompile $event) {
                $event->addRoute('/^users\/(?P<users_id>[\d]+)\/edit\/mumble/i', __NAMESPACE__ . '\Edit');
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

                $event->addNavigationItem(\Chat\Config::get('URL') . 'users/' . $userID . '/edit/mumble', 'Edit Mumble Info');
            }
        );

        $listeners[] = array(
            'event'    => \Chat\WebSocket\Events\AddPeriodicTimer::EVENT_NAME,
            'listener' => function (\Chat\WebSocket\Events\AddPeriodicTimer $event) {
                $event->addTimer(30, function() {
                    static $oldMumbleUsers;

                    $mumbleUsers = Service::getMumbleUserInfo();

                    //Skip sending data if its the same stuff as last time (waste of bandwidth).
                    if ($oldMumbleUsers == $mumbleUsers) {
                        return;
                    }

                    $oldSteamUsers = $mumbleUsers;

                    \Chat\WebSocket\Application::sendToAll('MUMBLE_USER_INFO', $mumbleUsers);
                });
            }
        );

        $listeners[] = array(
            'event'    => \Chat\WebSocket\Events\OnOpen::EVENT_NAME,
            'listener' => function (\Chat\WebSocket\Events\OnOpen $event) {
                $mumbleUsers = Service::getMumbleUserInfo();

                $event->getConnection()->send('MUMBLE_USER_INFO', $mumbleUsers);
            }
        );

        $listeners[] = array(
            'event'    => \Chat\Events\JavascriptCompile::EVENT_NAME,
            'listener' => function (\Chat\Events\JavascriptCompile $event) {
                //Add mumble to every page
                $event->addScript(\Chat\Config::get('URL') . 'plugins/mumble/www/templates/html/js/mumble.js');
            }
        );

        return $listeners;
    }
}