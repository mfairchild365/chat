<?php
namespace Chat\Plugins\Steam;

class Login implements \Chat\ViewableInterface
{
    public function getPageTitle()
    {
        return "Steam Login";
    }

    public function getURL()
    {
        return \Chat\Config::get('URL') . 'login/steam';
    }
}