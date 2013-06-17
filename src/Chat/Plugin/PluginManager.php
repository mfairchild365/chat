<?php
namespace Chat\Plugin;

class PluginManager
{
    protected static $eventsManager = false;

    protected static $options = array(
        'internal_plugins' => array(),
        'external_plugins' => array()
    );

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
}