<?php
namespace Chat\Setting;

class Routes extends \RegExpRouter\RoutesInterface
{
    public function getGetRoutes()
    {
        return array();
    }

    public function getPostRoutes()
    {
        return array('/^admin$/' => 'Edit');
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