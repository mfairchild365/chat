<?php
namespace Chat\User;

class RecordList extends \DB\RecordList
{
    public function getDefaultOptions()
    {
        $options = array();
        $options['itemClass'] = '\Chat\User\Record';
        $options['listClass'] = __CLASS__;

        return $options;
    }

    public static function getAllOnline($options = array())
    {
        //Build the list
        $options['sql'] = "SELECT id
                           FROM users
                           WHERE status = 'ONLINE'";

        return self::getBySql($options);
    }

    public static function getAll($options = array())
    {
        //Build the list
        $options['sql'] = "SELECT id
                           FROM users";

        return self::getBySql($options);
    }
}