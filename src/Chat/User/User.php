<?php
namespace Chat\User;

class User extends \DB\Record implements \Chat\Renderable
{
    protected $id;           //INT(32)
    protected $first_name;   //VARCHAR(256)
    protected $last_name;    //VARCHAR(256)
    protected $date_created; //DATETIME
    protected $date_edited;  //DATETIME
    protected $email;        //VARCHAR(256)
    protected $password;     //VARCHAR(256)
    protected $username;     //VARCHAR(256)
    protected $role;         //ENUM('ADMIN', 'USER')
    protected $status;       //ENUM('ACTIVE', 'BANNED');
    protected $chat_status;  //ENUM('AVAILABLE', 'BUSY', 'OFFLINE')

    public static function getByID($id)
    {
        return self::getByAnyField(__CLASS__, 'id', (int)$id);
    }

    public static function getByUsername($username)
    {
        return self::getByAnyField(
            __CLASS__,
           'username',
            \DB\RecordList::escapeString($username)
        );
    }

    public static function getByEmail($email)
    {
        return self::getByAnyField(
            __CLASS__,
            'email',
            \DB\RecordList::escapeString($email)
        );
    }

    public function keys()
    {
        return array('id');
    }

    public static function getTable()
    {
        return 'users';
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

    public function render()
    {
        //Convert this object to an array
        $data = $this->toArray();

        //Don't send the password
        unset($data['password']);

        return $data;
    }

    public function __get($var)
    {
        if (isset($this->$var)) {
            return $this->$var;
        }
    }

    public function __set($var, $value)
    {
        if (!property_exists(__CLASS__, $var)) {
            throw new \Exception("Trying to set the value of a non-existent field");
        }

        $this->$var = $value;
    }

    public function getURL()
    {
        if (!$this->id) {
            return false;
        }

        return \Chat\Config::get("URL") . "users/" . $this->id;
    }

    public function getEditURL()
    {
        if (!$url = $this->getURL()) {
            return false;
        }

        return $url . '/edit';
    }
}