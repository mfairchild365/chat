<?php
namespace Chat;

$config_file = __DIR__ . '/../config.sample.php';

if (file_exists(__DIR__ . '/../config.inc.php')) {
    $config_file = __DIR__ . '/../config.inc.php';
}

require_once $config_file;

use RegExpRouter as RegExpRouter;

// Initialize App, and construct everything
$app = new Controller($_GET);

//Render Away
$savvy = new OutputController($app->options);
$savvy->addGlobal('app', $app);

echo $savvy->render($app);