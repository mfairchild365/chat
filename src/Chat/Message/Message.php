<?php
namespace Chat\Message;

class Message extends \Chat\Record
{
    public $id;           //INT(32)
    public $users_id;     //VARCHAR(45)
    public $message;      //TEXT
    public $date_created; //DATETIME
    public $date_edited;  //DATETIME

    public static function getByID($id)
    {
        return self::getByAnyField(__CLASS__, 'id', (int)$id);
    }

    public function keys()
    {
        return array('id');
    }

    public static function getTable()
    {
        return 'messages';
    }

    public function insert()
    {
        $this->date_created = \Chat\Util::epochToDateTime();
        $this->date_edited  = \Chat\Util::epochToDateTime();

        return parent::insert();
    }

    public function update()
    {
        $this->date_edited = \Chat\Util::epochToDateTime();

        return parent::update();
    }

    public static function createNewMessage($userId, $message)
    {
        $record = new self();

        $record->users_id = $userId;
        $record->message = $message;

        $record->save();

        return $record;
    }
}