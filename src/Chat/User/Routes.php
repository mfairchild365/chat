<?php
namespace Chat\User;

class Routes extends \RegExpRouter\RoutesInterface
{
    public function getGetRoutes()
    {
        return array(
            '/^users\/(?P<id>[\d]+)$/i' => 'View'
        );
    }

    public function getPostRoutes()
    {
        return array(
            '/^users\/(?P<id>[\d]+)\/edit$/i' => 'Edit',
            '/^register$/i' => 'Register',
            '/^logout/i' => 'Logout',
            '/^login/i' => 'Login',
        );
    }

    public function getDeleteRoutes()
    {
        return array();
    }

    public function getPutRoutes()
    {
        return array();
    }
}