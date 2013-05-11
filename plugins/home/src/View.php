<?php
namespace Chat\Plugins\Home;

class View implements \Chat\ViewableInterface
{
    public function getPageTitle()
    {
        return "Home";
    }

    public function getURL()
    {
        return \Chat\Config::get('URL') . 'home';
    }
}