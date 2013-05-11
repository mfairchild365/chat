<?php
namespace Chat\Events;

class SubNavCompile extends \Symfony\Component\EventDispatcher\Event
{
    const EVENT_NAME = 'navigation.sub.compile';

    public $navigation = array();

    public function __construct($navigation)
    {
        $this->navigation = $navigation;
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