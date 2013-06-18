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
        foreach ($plugins as $name=>$info) {
            $class = $baseNamespace . ucfirst($name) . "\\Initialize";
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
        $class = '\\Chat\\Plugins\\' . strtoupper($name) . '\\Plugin';
        self::$options['external_plugins'][$name] = new $class;
    }

    public static function getExternalPlugins()
    {
        return self::$options['external_plugins'];
    }

    public static function getPluginNameFromClass($class) {
        $parts = explode('\\', $class);

        if (!isset($parts[2])) {
            return false;
        }

        return $parts[2];
    }

    public function handlePost($get, $post, $files)
    {
        print_r($post);
        echo "handling post"; exit();

        //TODO:  Check against current plugins.  Enable currently disabled plugins.  Disable currently Enabled plugins

        //TODO: Change to abstract, with the following methods.
        $plugin->install();
        $plugin->uninstall();
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