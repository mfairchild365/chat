<?php
namespace Chat;

class Controller
{

    public $output = null;
    
    public $dispatcher = null;

    public $options = array(
        'model'  => false,
        'format' => 'html',
        'enabled_plugins' => array('home'),
    );

    public function __construct($options = array())
    {
        $this->options = $options + $this->options;
        
        $this->dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();

        $this->initializePlugins();

        try {
            if (!empty($_POST)) {
                $this->handlePost();
            }
            $this->run();
        } catch (\Exception $e) {
            $this->output = $e;
        }
    }

    public function initializePlugins()
    {
        foreach ($this->options['enabled_plugins'] as $plugin) {

            $class = "Chat\\Plugins\\" . ucfirst($plugin) . "\\Initialize";
            $plugin = new $class($this->options);
            $plugin->initialize();
            foreach ($plugin->getEventListeners() as $listener) {
                $this->dispatcher->addListener($listener['event'], $listener['listener']);
            }
        }
    }

    public function getRoutes()
    {
        $event = $this->dispatcher->dispatch('routes.compile', new \Chat\CompileRoutesEvent(array()));

        return $event->getRoutes();
    }

    /**
     * Populate the actionable items according to the view map.
     *
     * @throws Exception if view is unregistered
     */
    public function run()
    {
        if (!isset($this->options['model'])
            || false === $this->options['model']) {
            throw new \Exception('Un-registered view', 404);
        }

        $this->output = new $this->options['model']($this->options);
    }

    public function handlePost()
    {
        $handler = new PostHandler($this->options, $_POST, $_FILES);

        return $handler->handle();
    }

    public static function redirect($url, $exit = true)
    {
        header('Location: '.$url);
        if (!defined('CLI')
            && false !== $exit) {
            exit($exit);
        }
    }
}