<?php
namespace Chat\Plugins\Mumble;

class Service
{
    protected static $api = false;

    public static function getAPI()
    {
        if (!self::$api) {
            self::$api = \QuickMumble\Api::getService(\Chat\Setting\Service::getSettingValue('MUMBLE_API_URL'));
        }

        return self::$api;
    }

    protected static function userExists($mumbleName, $mumbleUsers)
    {
        foreach ($mumbleUsers as $user) {
            if ($user->name == $mumbleName) {
                return true;
            }
        }

        return false;
    }

    public static function getMumbleUserInfo()
    {
        //Get steam stats for all users.
        $map = array();

        foreach(\Chat\User\RecordList::getAll() as $user) {
            //Skip users that haven't connected to steam yet.
            if (!$user->mumble_name) {
                continue;
            }

            $map[$user->id] = $user->mumble_name;
        }

        $mumbleUsers = Service::getAPI()->getUsers();

        foreach ($mumbleUsers as $key=>$mumbleUser) {
            $mumbleUsers[$key]->status = 'online';
            $mumbleUsers[$key]->users_id = array_search($mumbleUser->name, $map);
        }

        foreach ($map as $id=>$name) {
            if (!self::userExists($name, $mumbleUsers)) {
                $user = new \QuickMumble\User();
                $user->name = $name;
                $user->users_id = $id;
                $user->status = 'offline';

                $mumbleUsers[] = $user;
            }
        }

        return $mumbleUsers;
    }
}