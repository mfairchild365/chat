<?php
namespace Chat;

class CompileRoutesEvent extends \Symfony\Component\EventDispatcher\Event
{
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