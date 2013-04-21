<?php
namespace Chat;

$config_file = __DIR__ . '/../config.sample.php';

if (file_exists(__DIR__ . '/../config.inc.php')) {
    $config_file = __DIR__ . '/../config.inc.php';
}

require_once $config_file;

$options = array(
    'baseURL' => Config::get('URL'),
    'srcDir'  => dirname(dirname(__FILE__)) . "/src/Chat/",
);

$router = new \RegExpRouter\Router($options);

// Initialize App, and construct everything
$app = new Controller($router->route($_SERVER['REQUEST_URI'], $_GET));

//Render Away
$savvy = new OutputController($app->options);
$savvy->addGlobal('app', $app);

echo $savvy->render($app);