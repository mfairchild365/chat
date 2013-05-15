<?php
use Chat\Config;

/**********************************************************************************************************************
 * php related settings
 */

ini_set('display_errors', true);

error_reporting(E_ALL);

Config::set('URL', 'http://localhost/mfairchild365/chat/'); //Trailing slash is important

/**********************************************************************************************************************
 * DB related settings
 */
Config::set('DB_HOST'     , 'localhost');
Config::set('DB_USER'     , 'user');
Config::set('DB_PASSWORD' , 'password');
Config::set('DB_NAME'     , 'database');


