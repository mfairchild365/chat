<?php
namespace Chat\User;

use Symfony\Component\HttpFoundation\Session\Session;

class Service
{
    protected static $session;

    public static function logIn(\Chat\User\User $user)
    {
        $session = self::getSession();
        $session->start();

        $session->set('user.id', $user->id);
    }

    public static function logOut()
    {
        $session = self::getSession();
        $session->clear();
        $session->invalidate();
    }

    public static function getCurrentUser()
    {
        $session = self::getSession();

        return User::getByID($session->get('user.id'));
    }

    public static function requireLogin()
    {
        if (!self::getCurrentUser()) {
            throw new RequiredLoginException();
        }
    }

    public static function getSession()
    {
        //Create a memcache session because Ratched requires it...
        if (!self::$session) {
            $memcache = new \Memcache;
            $memcache->connect('localhost');

            $handler = new \Symfony\Component\HttpFoundation\Session\Storage\Handler\MemcacheSessionHandler($memcache);
            $storage = new \Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage(array(),$handler);
            self::$session = new Session($storage);
        }

        return self::$session;
    }
}