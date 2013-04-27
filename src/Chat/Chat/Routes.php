<?php
namespace Chat\Chat;

class Routes extends \RegExpRouter\RoutesInterface
{
    public function getGetRoutes()
    {
        return array('/^$/' => 'View');
    }

    public function getPostRoutes()
    {
        return array();
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