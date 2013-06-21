<?php
namespace Chat\Plugin;

class PluginManager
{
    protected $eventsManager = false;

    protected $options = array(
        'internal_plugins' => array(),
        'external_plugins' => array()
    );

    protected static $singleton = false;

    /**
     * Initialize the signleton
     *
     * @param $eventsManager
     * @param array $options
     */
    protected function __construct($eventsManager, $options = array())
    {
        $this->options = $options + $this->options;

        $this->eventsManager = $eventsManager;
    }

    /**
     * Get the plugin manager singleton
     *
     * @return bool | \Chat\Plugin\PluginManager
     * @throws \Chat\Exception
     */
    public static function getManager()
    {
        if (!self::$singleton) {
            throw new \Chat\Exception("Plugin Manager has not been initialized yet", 500);
        }

        return self::$singleton;
    }

    /**
     * Preform tasks that are usually preformed on load such as,
     * set up include paths and initialize plugins.
     */
    public function load()
    {
        $this->initializeIncludePaths();

        \Chat\Plugin\PluginManager::autoRegisterExternalPlugins();

        $this->initializePlugins("Chat\\", $this->options['internal_plugins']);

        $this->initializePlugins("Chat\\Plugins\\", $this->getInstalledPlugins());
    }

    /**
     * Initialize the singleton
     *
     * @param $eventsManager
     * @param array $options
     * @throws \Chat\Exception
     */
    public static function initialize($eventsManager, $options = array())
    {
        if (self::$singleton) {
            throw new \Chat\Exception("Plugin Manager can only be initialized once", 500);
        }

        self::$singleton = new self($eventsManager, $options);
        self::$singleton->load();
    }

    /**
     * Set up include paths for plugins and libraries
     */
    protected function initializeIncludePaths()
    {
        set_include_path(
            implode(PATH_SEPARATOR, array(get_include_path())) . PATH_SEPARATOR
                .dirname(dirname(dirname(__DIR__))).'/plugins'
        );

        //Include plugin vendor directories
        foreach ($this->getInstalledPlugins() as $name=>$plugin) {
            set_include_path(
                implode(PATH_SEPARATOR, array(get_include_path())) . PATH_SEPARATOR
                    .dirname(dirname(dirname(__DIR__))).'/plugins/' . $name . '/vendor'
            );
        }
    }

    /**
     * Get a list of installed plugins
     *
     * @return array
     */
    public function getInstalledPlugins()
    {
        $records = PluginList::getAllPlugins();

        $plugins = array();

        foreach ($records as $record) {
            $plugins[$record->name] = $this->getPluginInfo($record->name);
        }

        return $plugins;
    }

    /**
     * Initializes a set of plugins.
     *
     * @param $baseNamespace
     * @param array $plugins
     */
    protected function initializePlugins($baseNamespace, array $plugins)
    {
        foreach ($plugins as $name=>$info) {
            $class = $baseNamespace . ucfirst($name) . "\\Initialize";
            $plugin = new $class($this->options);
            $plugin->initialize();
            foreach ($plugin->getEventListeners() as $listener) {
                $this->eventsManager->addListener($listener['event'], $listener['listener']);
            }
        }
    }

    /**
     * Dispatch an event
     *
     * @param $eventName
     * @param \Symfony\Component\EventDispatcher\Event $event
     * @return mixed
     */
    public function dispatchEvent($eventName, \Symfony\Component\EventDispatcher\Event $event = null)
    {
        return $this->eventsManager->dispatch($eventName, $event);
    }

    protected function autoRegisterExternalPlugins()
    {
        foreach (new \DirectoryIterator(dirname(dirname(dirname(__DIR__))) . '/plugins') as $fileInfo) {
            if ($fileInfo->isDir() && file_exists($fileInfo->getPath() . '/' .$fileInfo->getFilename() . '/src/Plugin.php')) {
                $this->registerExternalPlugin($fileInfo->getFilename());
            }
        }
    }

    protected function registerExternalPlugin($name)
    {
        $class = '\\Chat\\Plugins\\' . strtoupper($name) . '\\Plugin';
        $this->options['external_plugins'][$name] = new $class;
    }

    public function getExternalPlugins()
    {
        return $this->options['external_plugins'];
    }

    public function getPluginNameFromClass($class) {
        $parts = explode('\\', $class);

        if (!isset($parts[2])) {
            return false;
        }

        return strtolower($parts[2]);
    }

    public function getPluginNamespaceFromName($name)
    {
        return '\\Chat\\Plugins\\' . strtoupper($name) . '\\';
    }

    public function getPluginInfo($name) {
        $class = $this->getPluginNamespaceFromName($name) . 'Plugin';

        return new $class();
    }

    public static function autoload($class)
    {
        //try a basic PSR-0 load first

        $file = str_replace(array('_', '\\'), '/', $class).'.php';
        if ($fullpath = stream_resolve_include_path($file)) {
            include $fullpath;
            return true;
        }

        //take of the plugin namespace
        $tmp = str_replace("Chat\\Plugins\\", "", $class, $count);

        //if the plugin namespace wasn't found... don't continue
        if (!$count) {
            return false;
        }

        $parts = explode("\\", $tmp);

        //If there is nothing after the plugin, don't continue.
        if (!$plugin = array_shift($parts)) {
            return false;
        }

        //start the starting directory (plugin/src/) for plugin classes
        $file = strtolower($plugin) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;

        //convert the namespace to a path
        $file .=  implode(DIRECTORY_SEPARATOR, $parts).'.php';

        if ($fullpath = stream_resolve_include_path($file)) {
            include $fullpath;
            return true;
        }

        return false;
    }
}