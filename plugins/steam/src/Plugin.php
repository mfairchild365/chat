<?php
namespace Chat\Plugins\Steam;

class Plugin extends \Chat\Plugin\PluginInterface
{
    public function onInstall()
    {
        //TODO: add the steam_id column to the users table
        $sql = 'ALTER TABLE users
                ADD steam_id_64 VARCHAR(256)';

        /**
         * TODO: add hooks to db class, so that $user->steam_id_64 is accessible.
         * Hook would be something like DB::getFields() and append the steam_id_64 field.
         * It won't show up as a property in the code, but it should work like this.
         */

        return false;
    }

    public function onUninstall()
    {
        //TODO: remove the steam_id column from the users table
        $sql = 'ALTER TABLE users
                DROP COLUMN steam_id_64';

        return false;
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
