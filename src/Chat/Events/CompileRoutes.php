<?php
namespace Chat\Events;

class CompileRoutes extends \Symfony\Component\EventDispatcher\Event
{
    const EVENT_NAME = 'routes.compile';

    protected $routes = array();
    
    public function __construct($routes)
    {
        $this->routes = $routes;
    }
    
    public function getRoutes()
    {
        return $this->routes;
    }
    
    public function addRoute($regex, $class)
    {
        $this->routes[$regex] = $class;
    }
}