<?php
namespace Chat\Plugin;

class PluginManager implements \Chat\PostHandlerInterface, \Chat\ViewableInterface
{
    protected static $eventsManager = false;

    protected static $options = array(
        'internal_plugins' => array(),
        'external_plugins' => array()
    );

    function __construct($options = array())
    {
        if (!$user = \Chat\User\Service::getCurrentUser()) {
            throw new \Chat\User\RequiredLoginException();
        }

        if ($user->role != 'ADMIN') {
            throw new \Chat\User\NotAuthorizedException();
        }
    }

    /**
     * @param array $options
     */
    public static function initialize($options = array())
    {
        self::$eventsManager = new \Symfony\Component\EventDispatcher\EventDispatcher();

        self::$options = $options + self::$options;

        self::initializePlugins("Chat\\", self::$options['internal_plugins']);
        self::initializePlugins("Chat\\Plugins\\", self::$options['external_plugins']);
    }

    public static function initializePlugins($baseNamespace, array $plugins)
    {
        foreach ($plugins as $plugin) {
            $class = $baseNamespace . ucfirst($plugin) . "\\Initialize";
            $plugin = new $class(self::$options);
            $plugin->initialize();
            foreach ($plugin->getEventListeners() as $listener) {
                self::$eventsManager->addListener($listener['event'], $listener['listener']);
            }
        }
    }

    public static function dispatchEvent($eventName, \Symfony\Component\EventDispatcher\Event $event = null)
    {
        if (!self::$eventsManager) {
            throw new \Chat\Exception("Plugin Manager has not been initialized yet", 500);
        }

        return self::$eventsManager->dispatch($eventName, $event);
    }

    public static function autoRegisterExternalPlugins()
    {
        foreach (new \DirectoryIterator(dirname(dirname(dirname(__DIR__))) . '/plugins') as $fileInfo) {
            if ($fileInfo->isDir() && file_exists($fileInfo->getPath() . '/' .$fileInfo->getFilename() . '/src/Plugin.php')) {
                self::registerExternalPlugin($fileInfo->getFilename());
            }
        }
    }

    public static function registerExternalPlugin($name)
    {
        self::$options['external_plugins'][] = $name;

        //Make sure we only have unique values.
        self::$options['external_plugins'] = array_unique(self::$options['external_plugins']);
    }

    public static function getExternalPlugins()
    {
        return self::$options['external_plugins'];
    }

    public function handlePost($get, $post, $files)
    {
        echo "handling post"; exit();
    }

    public function getPageTitle()
    {
        return 'Manage Plugins';
    }

    public function getURL()
    {
        return $this->getEditURL();
    }

    public function getEditURL()
    {
        return \Chat\Config::get('URL') . 'admin/plugins';
    }
}