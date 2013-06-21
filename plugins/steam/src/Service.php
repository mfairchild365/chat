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
}