<?php
namespace Chat\Setting;

class Service
{
    public static function getSettingValue($name)
    {
        if (!$setting = Setting::getBySettingName($name)) {
            throw new \Exception('Unknown setting named called: ' .$name, 500);
        }

        return $setting->setting_value;
    }
}