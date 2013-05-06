<?php
require_once dirname(__DIR__) . "/init.php";

function exec_sql($db, $sql, $message, $fail_ok = false)
{
    echo $message.PHP_EOL;

    //Replace all instances of DEFAULTDATABASENAME with the config db name.
    $sql = str_replace('DEFAULTDATABASENAME', \Chat\Config::get("DB_NAME"), $sql);

    try {
        $result = true;
        if ($db->multi_query($sql)) {
            do {
                /* store first result set */
                if ($result = $db->store_result()) {
                    $result->free();
                }

                if (!$db->more_results()) {
                    break;
                }
            } while ($db->next_result());
        } else {
            echo "Query Failed: " . $db->error . PHP_EOL;
        }
    } catch (Exception $e) {
        $result = false;
        if (!$fail_ok) {
            echo 'The query failed:'.$result->errorInfo();
            exit();
        }
    }
    echo 'finished.'.PHP_EOL;
    echo '------------------------------------------'.PHP_EOL;
    return $result;
}

$db = \Chat\Util::getDB();

$sql = "";

if (isset($argv[1]) && $argv[1] == '-f') {
    echo "Deleting old install" . PHP_EOL;
    $sql .= "SET FOREIGN_KEY_CHECKS=0;
             DROP TABLE IF EXISTS users;
             DROP TABLE IF EXISTS messages;
             SET FOREIGN_KEY_CHECKS=1;";
}
$sql .= file_get_contents(dirname(__DIR__) . "/data/database.sql");

exec_sql($db, $sql, 'updatating database');

//add a system user if it does not already exist.  This user can be used to issue notices, etc.
if (!\Chat\User\User::getByEmail('system')) {
    \Chat\User\Register::registerUser('system', '', 'system', 'system');
}

//add default settings
if (!\Chat\Setting\Setting::getBySettingName('SITE_NAME')) {
    $setting = new \Chat\Setting\Setting();
    $setting->setting_name = "SITE_NAME";
    $setting->setting_value = "EasyChat Site";
    $setting->save();
}

if (!\Chat\Setting\Setting::getBySettingName('SITE_PASSWORD')) {
    $setting = new \Chat\Setting\Setting();
    $setting->setting_name = "SITE_PASSWORD";
    $setting->setting_value = ""; //leave empty for open registration
    $setting->save();
}