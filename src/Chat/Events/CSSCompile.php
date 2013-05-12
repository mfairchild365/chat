<?php
namespace Chat\Events;

class CSSCompile extends \Symfony\Component\EventDispatcher\Event
{
    const EVENT_NAME = 'css.compile';

    public $scripts = array();
    protected $view;

    public function __construct(\Chat\ViewableInterface $view)
    {
        $this->view = $view;
    }

    public function getScripts()
    {
        return array_unique($this->scripts);
    }

    public function getView()
    {
        return $this->view;
    }

    public function addScript($url)
    {
        $this->scripts[] = $url;
    }
}