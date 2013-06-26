<?php
//Initialize all settings and autoloaders
require_once dirname(dirname(dirname(__DIR__))) . "/init.php";

//Continually poll for new information.
while (true) {
    $steamUsers = Chat\Plugins\Steam\Service::getSteamUserInfo();

    file_put_contents(\Chat\Config::get('CACHE_DIR') . '/steam_users.php', serialize($steamUsers));

    //wait awhile for the next poll
    sleep(15);
}