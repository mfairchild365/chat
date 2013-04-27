<?php
//Initialize all settings and autoloaders
require_once(__DIR__ . "/init.php");

// Initialize App, and construct everything
$app = new \Chat\Controller($_GET);

//Render Away
$savvy = new \Chat\OutputController($app->options);
$savvy->addGlobal('app', $app);
$savvy->addGlobal('user', \Chat\User\Service::getCurrentUser());

echo $savvy->render($app);