<?php
namespace Chat\Chat;

class Routes extends \RegExpRouter\RoutesInterface
{
    public function getGetRoutes()
    {
        return array();
    }

    public function getPostRoutes()
    {
        return array(
            '/^$/' => 'View'
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