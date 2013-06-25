<?php
namespace Chat\Plugins\Steam;

class Service
{
    protected static $api = false;
    const TTL = 30;

    public static function getAPI()
    {
        if (!self::$api) {
            self::$api = \QuickSteam\Api::getService(\Chat\Setting\Service::getSettingValue('STEAM_API_KEY'));
        }

        return self::$api;
    }

    public static function getSteamUserInfo()
    {
        static $steamUsers;
        static $lastAccess;

        if ($steamUsers &&  time() <= $lastAccess + self::TTL) {
            echo "cached" . PHP_EOL;
            return $steamUsers;
        }

        //Get steam stats for all users.
        $map = array();

        foreach(\Chat\User\RecordList::getAll() as $user) {
            //Skip users that haven't connected to steam yet.
            if (!$user->steam_id_64) {
                continue;
            }

            $map[$user->id] = $user->steam_id_64;
        }

        $steamUsers = Service::getAPI()->getUsers(array_values($map));

        foreach ($steamUsers as $key=>$steamUser) {
            $steamUsers[$key]->users_id = array_search($steamUser->steamid, $map);
        }

        $lastAccess = time();
        return $steamUsers;
    }
}