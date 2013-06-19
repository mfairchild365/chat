<?php
namespace Chat\Plugins\Steam;

class Plugin extends \Chat\Plugin\PluginInterface
{
    public function onInstall()
    {
        $sql = 'ALTER TABLE users
                ADD steam_id_64 VARCHAR(256)';

        $db = \Chat\Util::getDB();
        $db->query($sql);

        /**
         * TODO: add hooks to db class, so that $user->steam_id_64 is accessible.
         * Hook would be something like DB::getFields() and append the steam_id_64 field.
         * It won't show up as a property in the code, but it should work like this.
         */

        return true;
    }

    public function onUninstall()
    {
        $sql = 'ALTER TABLE users
                DROP COLUMN steam_id_64';

        $db = \Chat\Util::getDB();
        $db->query($sql);

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
