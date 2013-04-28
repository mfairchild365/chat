<?php
namespace Chat\Message;

class RecordList extends \DB\RecordList
{
    public function getDefaultOptions()
    {
        $options = array();
        $options['itemClass'] = '\Chat\Message\Message';
        $options['listClass'] = __CLASS__;

        return $options;
    }

    public static function getAllMessages($options = array())
    {
        //Build the list
        $options['sql'] = "SELECT id
                           FROM messages
                           ORDER BY date_created ASC";

        return new self($options);
    }
}