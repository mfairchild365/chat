<?php
namespace Chat\Setting;

class Setting extends \Chat\Record
{
    protected $id;            //INT(32)
    protected $setting_name;  //VARCHAR(256) UNIQUE
    protected $setting_value; //VARCHAR(256)

    public static function getByID($id)
    {
        return self::getByAnyField(__CLASS__, 'id', (int)$id);
    }

    public static function getBySettingName($settingName)
    {
        return self::getByAnyField(__CLASS__, 'setting_name', $settingName);
    }

    public function keys()
    {
        return array('id');
    }

    public static function getTable()
    {
        return 'settings';
    }
}