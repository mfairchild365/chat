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

        $listeners[] = array(
            'event'    => \Chat\WebSocket\Events\OnOpen::EVENT_NAME,
            'listener' => function (\Chat\WebSocket\Events\OnOpen $event) {
                //Get the user
                //Set as online.
                if (!$user = $event->getConnection()->getUser()) {
                    $event->getConnection()->send('ERROR_AUTH', array('message'=>'You must log in'));
                    $event->getConnection()->close();
                    $event->stopPropagation();
                    return;
                }

                $user->chat_status = "ONLINE";
                $user->save();

                //Update the client's list with all users currently online.
                foreach (\Chat\User\RecordList::getAll() as $data) {
                    $event->getConnection()->send('USER_CONNECTED', $data);
                }

                //Send the client information about the logged in user
                $event->getConnection()->send('USER_INFORMATION', $user);

                //Tell everyone else that this guy just came online.
                if (\Chat\WebSocket\Application::getUserConnectionCount($user->id) == 1) {
                    \Chat\WebSocket\Application::sendToAll("USER_CONNECTED", $user);
                }
            },
            'priority' => 10
        );

        //Handle 'USER_UPDATE'
        $listeners[] = array(
            'event'    => \Chat\WebSocket\Events\OnMessage::EVENT_NAME,
            'listener' => function (\Chat\WebSocket\Events\OnMessage $event) {
                if ($event->getAction() != 'USER_UPDATE') {
                    return;
                }

                if (!isset($event->getData()['id'])) {
                    throw new \Chat\Exception("ID must be passed.");
                }

                if ($event->getData()['id'] != $event->getConnection()->getUser->id) {
                    throw new \Chat\Exception("ID must be passed.");
                }

                $object = User::getByID($event->getData()['id']);

                $object->synchronizeWithArray($event->getData());

                $object->save();

                \Chat\WebSocket\Application::sendToAll('USER_UPDATED', $object);
            }
        );

        return $listeners;
    }
}