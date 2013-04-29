<?php
require_once dirname(__DIR__) . "/init.php";

if (!isset($argv[1], $argv[2], $argv[3])) {
    echo "Usage: php userMod.php email key value" . PHP_EOL;
    exit();
}

if (!$user = \Chat\User\User::getByEmail($argv[1])) {
    echo "Sorry, user was not found." . PHP_EOL;
}

$user->$argv[2] = $argv[3];
$user->save();

echo "user has been updated" . PHP_EOL;
