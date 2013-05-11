<?php
namespace Chat\User;

use Chat\PostHandlerInterface;

class Register implements PostHandlerInterface, \Chat\ViewableInterface
{
    public static function registerUser($email, $password, $firstName = '', $lastName = '', $role = 'USER')
    {
        //Now check to see if we already have someone with the same email address.
        if (count(RecordList::getAllByEmail($email))) {
            throw new \Exception("That email address is already in use.", 400);
        }

        //hash the password
        if (!$password = self::hashPassword($password)) {
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

    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    function handlePost($get, $post, $files)
    {
        if ($sitePassword = \Chat\Setting\Service::getSettingValue('SITE_PASSWORD')) {
            if (!isset($post['site_password']) || empty($post['site_password'])) {
                throw new \Chat\Exception("You need to specify a site password", 400);
            }

            if ($post['site_password'] != $sitePassword) {
                throw new \Chat\Exception("Wrong Site password, please try again", 400);
            }
        }

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

        \Chat\Controller::redirect(
            \Chat\Config::get('URL') . "users/" . $user->id,
            new \Chat\FlashBagMessage("success", "Registration successful!")
        );
    }

    public function getEditURL()
    {
        return $this->getURL();
    }

    public function getURL()
    {
        return \Chat\Config::get('URL') . "register";
    }

    public function getPageTitle()
    {
        return "Register";
    }
}