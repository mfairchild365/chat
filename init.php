<?php
require __DIR__ . '/vendor/autoload.php';

$config_file = __DIR__ . '/config.sample.php';
if (file_exists(__DIR__ . '/config.inc.php')) {
    $config_file = __DIR__ . '/config.inc.php';
}

require_once $config_file;

//Establish a connection to the DB.
\Chat\Util::connectDB();

//Register the plugin autoloader
spl_autoload_register('\Chat\Plugin\PluginManager::autoload');

\Chat\Plugin\PluginManager::initialize(
    new \Symfony\Component\EventDispatcher\EventDispatcher(),
    array(
        'internal_plugins' => array(
            'Chat' => array(), //TODO:: make these actually reference their Plugin classes.
            'Message' => array(),
            'Setting' => array(),
            'User' => array(),
            'Plugin' => array(),
        )
    )
);