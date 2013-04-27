<?php
namespace Chat\User;

use Symfony\Component\HttpFoundation\Session\Session;

class Service
{
    public static function logIn(\Chat\User\User $user)
    {
        $session = new Session();
        $session->start();

        $session->set('user.id', $user->id);
    }

    public static function logOut()
    {
        $session = new Session();
        $session->clear();
        $session->invalidate();
    }

    public static function getCurrentUser()
    {
        $session = new Session();

        return User::getByID($session->get('user.id'));
    }
}