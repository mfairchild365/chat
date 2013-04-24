<?php
namespace Chat;

class Controller
{

    public $output = null;
    
    public static $dispatcher = null;

    public $options = array(
        'model'  => false,
        'format' => 'html',
    );

    public function __construct($options = array())
    {
        $this->options = $options + $this->options;
        
        self::$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();

        try {
            if (!empty($_POST)) {
                $this->handlePost();
            }
            $this->run();
        } catch (\Exception $e) {
            $this->output = $e;
        }
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