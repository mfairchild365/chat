<?php
namespace Chat\Plugins\Gravatar;

class Plugin extends \Chat\Plugin\PluginInterface
{
    public function onInstall()
    {
        return true;
    }

    public function onUninstall()
    {
        return true;
    }

    public function getName()
    {
        return 'Gravatar Support';
    }

    public function getDescription()
    {
        return 'Enabled gravatar support though out the system.';
    }
}
