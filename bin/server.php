<?php
//Initialize all settings and autoloaders
require_once dirname(__DIR__) . "/init.php";

use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Session\SessionProvider;

// we are the parent
echo date("Y-m-d H:i:s") . "Starting Server..." . PHP_EOL;

$app = &new \Chat\WebSocket\Application();

$memcache = new Memcache;
$memcache->connect('localhost');

$session = new SessionProvider(
    $app,
    new \Symfony\Component\HttpFoundation\Session\Storage\Handler\MemcacheSessionHandler($memcache)
);

//Start the actual server
$server = IoServer::factory(
    new WsServer($session),
    \Chat\Config::get('SERVER_PORT'),
    \Chat\Config::get('SERVER_ADDR')
);

//Dispatch event to get timers
$event = \Chat\Plugin\PluginManager::getManager()->dispatchEvent(
    \Chat\WebSocket\Events\AddPeriodicTimer::EVENT_NAME,
    new \Chat\WebSocket\Events\AddPeriodicTimer()
);

$timers = $event->getTimers();

foreach ($timers as $timer) {
    $server->loop->addPeriodicTimer($timer['interval'], $timer['callback']);
}

$server->run();