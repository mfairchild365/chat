<?php
require __DIR__ . '/vendor/autoload.php';

$config_file = __DIR__ . '/config.sample.php';
if (file_exists(__DIR__ . '/config.inc.php')) {
    $config_file = __DIR__ . '/config.inc.php';
}

require_once $config_file;

set_include_path(
    implode(PATH_SEPARATOR, array(get_include_path())) . PATH_SEPARATOR
        .dirname(__FILE__).'/plugins'
);

//Custom autoloader for plugins
function pluginAutoload($class)
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

//Register the plugin autoloader
spl_autoload_register("pluginAutoload");

//Establish a connection to the DB.
\Chat\Util::connectDB();

//Register Plugins
\Chat\Plugin\PluginManager::autoRegisterExternalPlugins();