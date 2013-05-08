<?php
namespace Chat\Chat;

class View implements \Chat\ViewableInterface
{
    function __construct($options = array())
    {
        //Require login
        \Chat\User\Service::requireLogin();
    }

    public function getURL()
    {
        return \Chat\Config::get('URL');
    }

    public function getPageTitle()
    {
        return "Chat";
    }
}