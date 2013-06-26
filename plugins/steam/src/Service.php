<?php
namespace Chat\Plugins\Steam;

class Service
{
    protected static $api = false;

    public static function getAPI()
    {
        if (!self::$api) {
            self::$api = \QuickSteam\Api::getService(\Chat\Setting\Service::getSettingValue('STEAM_API_KEY'));
        }

        return self::$api;
    }

    public static function getSteamUserInfo()
    {
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

        return $steamUsers;
    }

    public static function getCachedSteamUserInfo()
    {
        $cacheFile = \Chat\Config::get('CACHE_DIR') . '/steam_users.php';

        if (!file_exists($cacheFile)) {
            return false;
        }

        if (!$users = file_get_contents($cacheFile)) {
            return false;
        }

        if (!$users = unserialize($users)) {
            return false;
        }

        return $users;
    }
}