<?php
namespace Chat\User;

use Chat\PostHandlerInterface;

class Login extends User implements PostHandlerInterface
{
    public function handlePost($get, $post, $files)
    {
        //We need to make sure that the username and password are set in the post array
        if (!isset($post['username']) || empty($post['username'])) {
            throw new \Exception("You need to specify a username", 400);
        }

        if (!isset($post['password']) || empty($post['password'])) {
            throw new \Exception("You need to specify a password", 400);
        }

        //Find the correct user.
        $found = false;
        if ($user = User::getByUsername($post['username'])) {
            $found = true;
        }

        if (!$found && $user = User::getByEmail($post['username'])) {
            $found = true;
        }

        if (!$found) {
            throw new \Exception("We were not able to find that user.  Are you sure you have an account?", 400);
        }

        if (!password_verify($post['password'], $user->password)) {
            throw new \Exception("Sorry, we could not find that account.", 400);
        }

        Service::logIn($user);
        \Chat\Controller::redirect(\Chat\Config::get('URL'));
    }
}