<?php
namespace Chat\Events;

class NavigationSubCompile extends \Symfony\Component\EventDispatcher\Event
{
    const EVENT_NAME = 'navigation.sub.compile';

    public $navigation = array();
    protected $view;

    public function __construct(\Chat\ViewableInterface $view)
    {
        $this->view = $view;
    }

    public function getNavigation()
    {
        return $this->navigation;
    }

    public function getView()
    {
        return $this->view;
    }

    public function addNavigationItem($url, $title)
    {
        $this->navigation[$url] = $title;
    }
}