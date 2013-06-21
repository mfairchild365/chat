<?php
namespace Chat\Plugins\Steam;

class Plugin extends \Chat\Plugin\PluginInterface
{
    public function onInstall()
    {
        $db = \Chat\Util::getDB();

        //Add the steam_id_64 col to the users table.
        $sql = 'ALTER TABLE users
                ADD steam_id_64 VARCHAR(256)';

        $db->query($sql);

        //Add the STEAM_API_KEY row to the settings table
        $setting = new \Chat\Setting\Setting();
        $setting->setting_name = 'STEAM_API_KEY';
        $setting->save();

        return true;
    }

    public function onUninstall()
    {
        $db = \Chat\Util::getDB();

        $sql = 'ALTER TABLE users
                DROP COLUMN steam_id_64';

        $db->query($sql);

        //remove the STEAM_API_KEY row
        $setting = \Chat\Setting\Setting::getBySettingName('STEAM_API_KEY');
        $setting->delete();

        return true;
    }

    public function getName()
    {
        return 'Steam integration';
    }

    public function getDescription()
    {
        return 'Enables steam integration so that users can sign in with steam and stats will appear in chat.';
    }
}
