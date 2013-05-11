<?php
namespace Chat\Events;

class NavigationMainCompile extends \Symfony\Component\EventDispatcher\Event
{
    const EVENT_NAME = 'navigation.main.compile';

    public $navigation = array();

    public function __construct()
    {

    }

    public function getNavigation()
    {
        return $this->navigation;
    }

    public function addNavigationItem($url, $title)
    {
        $this->navigation[$url] = $title;
    }
}