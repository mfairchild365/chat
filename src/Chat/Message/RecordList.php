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

    public static function getLatestMessages($limit = 15, $options = array())
    {
        //Build the list
        $options['sql'] = "SELECT id
                           FROM messages
                           ORDER BY id DESC
                           LIMIT " . (int)$limit;

        return new self($options);
    }

    public static function getMessagesOlderThanID($id, $limit = 15, $options = array())
    {
        //Build the list
        $options['sql'] = "SELECT id
                           FROM messages
                           WHERE id < " . (int)$id ."
                           ORDER BY id DESC
                           LIMIT " . (int)$limit;

        return new self($options);
    }
}