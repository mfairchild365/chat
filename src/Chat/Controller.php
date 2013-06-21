<?php
namespace Chat;

class Controller
{
    public $output = null;

    public $options = array(
        'model'  => false,
        'format' => 'html'
    );

    public function __construct($options = array())
    {
        $this->options = $options + $this->options;
        $this->options['current_url'] = Util::getCurrentURL();

        $this->route();

        try {
            if (!empty($_POST)) {
                $this->handlePost();
            }
            $this->run();
        } catch (\Exception $e) {
            $this->output = $e;
        }
    }

    public function getPluginRoutes()
    {
        $event = \Chat\Plugin\PluginManager::getManager()->dispatchEvent('routes.compile', new \Chat\Events\RoutesCompile(array()));

        return $event->getRoutes();
    }
    
    public function route()
    {
        $options = array(
            'baseURL' => \Chat\Config::get('URL'),
            'srcDir'  => dirname(__FILE__) . "/",
        );

        $router = new \RegExpRouter\Router($options);
        $router->setRoutes($this->getPluginRoutes());

        // Initialize App, and construct everything
        $this->options = $router->route($_SERVER['REQUEST_URI'], $this->options);
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
            throw new \Chat\Exception('Un-registered view', 404);
        }

        $this->output = new $this->options['model']($this->options);

        if (!$this->output instanceof \Chat\ViewableInterface) {
            throw new \Chat\Exception("All Output must be an instance of \\Chat\\ViewableInterface");
        }
    }

    public function handlePost()
    {
        $object = new $this->options['model']($this->options);

        if (!$object instanceof \Chat\PostHandlerInterface) {
            throw new Exception("All Post Handlers must be an instance of \\Chat\\PostHandlerInterface");
        }

        return $object->handlePost($this->options, $_POST, $_FILES);
    }

    public function getFlashBagMessages()
    {
        return \Chat\User\Service::getSession()->getFlashBag()->all();
    }

    public static function addFlashBagMessage(\Chat\FlashBagMessage $message)
    {
        $session = \Chat\User\Service::getSession();
        $session->getFlashBag()->add('alert', $message);
    }

    public static function redirect($url, \Chat\FlashBagMessage $message = NULL, $exit = true)
    {
        if ($message) {
            self::addFlashBagMessage($message);
        }

        header('Location: '.$url);
        if (!defined('CLI')
            && false !== $exit) {
            exit($exit);
        }
    }
}