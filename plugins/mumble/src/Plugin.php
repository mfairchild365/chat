<?php
namespace Chat\Plugins\Mumble;

class Plugin extends \Chat\Plugin\PluginInterface
{
    public function onInstall()
    {
        $db = \Chat\Util::getDB();

        //Add the mumble_name col to the users table.
        $sql = 'ALTER TABLE users
                ADD mumble_name VARCHAR(256)';

        $db->query($sql);

        //Add the STEAM_API_KEY row to the settings table
        $setting = new \Chat\Setting\Setting();
        $setting->setting_name = 'MUMBLE_API_URL';
        $setting->save();

        return true;
    }

    public function onUninstall()
    {
        $db = \Chat\Util::getDB();

        $sql = 'ALTER TABLE users
                DROP COLUMN mumble_name';

        $db->query($sql);

        //remove the MUMBLE_API_URL row
        $setting = \Chat\Setting\Setting::getBySettingName('MUMBLE_API_URL');
        $setting->delete();

        return true;
    }

    public function getName()
    {
        return 'Mumble integration';
    }

    public function getDescription()
    {
        return 'Enables mumble integration so that users can associate their profile with mumble.';
    }
}
