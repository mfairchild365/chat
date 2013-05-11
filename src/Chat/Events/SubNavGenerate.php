<?php
namespace Chat\Events;

class SubNavGenerate extends \Symfony\Component\EventDispatcher\Event
{
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