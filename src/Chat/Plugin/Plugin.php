<?php
namespace Chat\Plugin;

class Plugin extends \Chat\Record
{
    protected $id;           //INT(32)
    protected $name;         //VARCHAR(45)

    public static function getByID($id)
    {
        return self::getByAnyField(__CLASS__, 'id', $id);
    }

    public static function getByName($name)
    {
        return self::getByAnyField(__CLASS__, 'name', $name);
    }

    public function keys()
    {
        return array('id');
    }

    public static function getTable()
    {
        return 'plugins';
    }
}