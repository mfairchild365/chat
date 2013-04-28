<?php
namespace Chat\User;

use Chat\PostHandlerInterface;

class Register implements PostHandlerInterface
{
    public static function registerUser($email, $password, $firstName = '', $lastName = '', $role = 'USER')
    {
        //Now check to see if we already have someone with the same email address.
        if (count(RecordList::getAllByEmail($email))) {
            throw new \Exception("That email address is already in use.", 400);
        }

        //hash the password
        if (!$password = password_hash($password, PASSWORD_BCRYPT)) {
            throw new \Exception("There was an error handling the password.", 500);
        }

        $user              = new User();
        $user->email       = $email;
        $user->username    = $email;
        $user->password    = $password;
        $user->role        = $role;
        $user->status      = "ACTIVE";
        $user->chat_status = "OFFLINE";
        $user->first_name  = $firstName;
        $user->last_name   = $lastName;

        //save the new user.
        $user->save();

        return $user;
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

        if (!isset($post['username']) || empty($post['username'])) {
            $post['username'] = $post['email'];
        }

        $user = self::registerUser($post['email'], $post['password']);

        //User is created... don't reset email and password
        unset($post['email']);
        unset($post['password']);

        $user->synchronizeWithArray($post);

        $user->save();

        Service::logIn($user);

        \Chat\Controller::redirect(\Chat\Config::get('URL') . "users/" . $user->id);
    }
}