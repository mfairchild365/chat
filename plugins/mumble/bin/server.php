<?php
//Initialize all settings and autoloaders
require_once dirname(dirname(dirname(__DIR__))) . "/init.php";

//Continually poll for new information.
while (true) {
    $mumbleUsers = Chat\Plugins\Mumble\Service::getMumbleUserInfo();
    $serverInfo = Chat\Plugins\Mumble\Service::getAPI()->getServer();

    file_put_contents(\Chat\Config::get('CACHE_DIR') . '/mumble_users.php', serialize($mumbleUsers));
    file_put_contents(\Chat\Config::get('CACHE_DIR') . '/mumble_server.php', serialize($serverInfo));

    //wait awhile for the next poll
    sleep(15);
}