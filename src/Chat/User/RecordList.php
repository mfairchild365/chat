<?php
namespace Chat\User;

class RecordList extends \DB\RecordList
{
    public function getDefaultOptions()
    {
        $options = array();
        $options['itemClass'] = '\Chat\User\User';
        $options['listClass'] = __CLASS__;

        return $options;
    }

    public static function getAllOnline($options = array())
    {
        //Build the list
        $options['sql'] = "SELECT id
                           FROM users
                           WHERE status = 'ONLINE'";

        return new self($options);
    }

    public static function getAll($options = array())
    {
        //Build the list
        $options['sql'] = "SELECT id
                           FROM users";

        return new self($options);
    }

    public static function getAllByEmail($email, $options = array())
    {
        //Build the list
        $options['sql'] = "SELECT id
                           FROM users
                           WHERE email = '" . self::escapeString($email) . "'";

        return new self($options);
    }
}