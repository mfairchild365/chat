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
        set_include_path(
            implode(PATH_SEPARATOR, array(get_include_path())) . PATH_SEPARATOR
                .dirname(dirname(dirname(__DIR__))).'/plugins'
        );

        self::$eventsManager = new \Symfony\Component\EventDispatcher\EventDispatcher();

        self::$options = $options + self::$options;

        self::initializePlugins("Chat\\", self::$options['internal_plugins']);
        self::initializePlugins("Chat\\Plugins\\", self::getInstalledPlugins());
    }

    public static function getInstalledPlugins()
    {
        $records = PluginList::getAllPlugins();

        $plugins = array();

        foreach ($records as $record) {
            $plugins[$record->name] = $record->getInfo();
        }

        return $plugins;
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

        return strtolower($parts[2]);
    }

    public static function getPluginNamespaceFromName($name)
    {
        return '\\Chat\\Plugins\\' . strtoupper($name) . '\\';
    }

    public static function getPluginInfo($name) {
        $class = self::getPluginNamespaceFromName($name) . 'Plugin';

        return new $class();
    }

    public static function autoload($class)
    {
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

    public function handlePost($get, $post, $files)
    {
        if (!isset($post['enabled_plugins'])) {
            $post['enabled_plugins'] = array();
        }

        //Find out which ones we need to install, and install them.
        foreach ($post['enabled_plugins'] as $name) {
            $info = self::getPluginInfo($name);

            //Skip because it is already installed.
            if ($info->isInstalled()) {
                continue;
            }

            if ($info->install()) {
               \Chat\Controller::addFlashBagMessage(new \Chat\FlashBagMessage('success', $info->getName() . ' was installed'));
            } else {
               \Chat\Controller::addFlashBagMessage(new \Chat\FlashBagMessage('error', 'There was an error installing ' . $info->getName()));
           }
        }

        //Uninstall plugins
        foreach (PluginList::getAllPlugins() as $plugin) {
            if (!in_array($plugin->name, $post['enabled_plugins'])) {
                $info = $plugin->getInfo();
                if ($info->uninstall()) {
                    \Chat\Controller::addFlashBagMessage(new \Chat\FlashBagMessage('success', $info->getName() . ' was uninstalled'));
                } else {
                    \Chat\Controller::addFlashBagMessage(new \Chat\FlashBagMessage('error', 'There was an error uninstalling ' . $info->getName()));
                }
            }
        }

        \Chat\Controller::redirect(
            $this->getEditURL(),
            new \Chat\FlashBagMessage('success',  'Finished Updating Plugins')
        );
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