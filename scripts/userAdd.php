<?php
require_once dirname(__DIR__) . "/init.php";

if (!isset($argv[1], $argv[2])) {
    echo "Usage: php userAdd.php email password [role(ADMIN|USER) username firstname lastname]" . PHP_EOL;
    exit();
}

$user = \Chat\User\Register::registerUser($argv[1], $argv[2]);

//role
if (isset($argv[3])) {
    $user->role = strtoupper($argv[3]);
}

//username
if (isset($argv[4])) {
    $user->username = strtoupper($argv[4]);
}

//firstname
if (isset($argv[5])) {
    $user->first_name = strtoupper($argv[5]);
}

//lastname
if (isset($argv[6])) {
    $user->last_name = strtoupper($argv[6]);
}

$user->save();

echo "User " . $user->username . " was created" . PHP_EOL;