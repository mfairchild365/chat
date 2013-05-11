<?php
namespace Chat;

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
            throw new \Exception("Plugin Manager has not been initialized yet", 500);
        }

        return self::$eventsManager->dispatch($eventName, $event);
    }
}