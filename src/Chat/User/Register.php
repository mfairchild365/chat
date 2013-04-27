<?php
namespace Chat\User;

class Register extends User
{
    function __construct($options = array())
    {
        if (isset($options['id']) && $object = self::getByID($options['id'])) {
            $this->synchronizeWithArray($object->toArray());
        }
    }

    function handlePost($get, $post, $files)
    {
        //The only required fields are email and password, so lets check!
        if (!isset($post['password']) || empty($post['password'])) {
            throw new \Exception("You need to specify a password", 400);
        }

        if (!isset($post['password_verify']) || empty($post['password_verify'])) {
            throw new \Exception("You need to specify a verify password", 400);
        }

        if ($post['password'] != $post['password_verify']) {
            throw new \Exception("Oh no! Your passwords did not match!", 400);
        }

        if (!isset($post['email']) || empty($post['email'])) {
            throw new \Exception("You need to specify an email adddress", 400);
        }

        //Now check to see if we already have someone with the same email address.
        if (count(RecordList::getAllByEmail($post['email']))) {
            throw new \Exception("That email address is already in use.", 400);
        }

        if (!isset($post['username']) || empty($post['username'])) {
            $post['username'] = $post['email'];
        }

        //hash the password
        if (!$post['password'] = password_hash($post['password'], PASSWORD_BCRYPT)) {
            throw new \Exception("There was an error handling the password.", 500);
        }

        $this->synchronizeWithArray($post);

        //Set some defaults
        $this->role        = "USER";
        $this->status      = "ACTIVE";
        $this->chat_status = "OFFLINE";

        $this->save();

        Service::logIn($this);

        \Chat\Controller::redirect(\Chat\Config::get('URL') . "users/" . $this->id);
    }
}