<?php
namespace Chat\Plugins\Notifications;

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
        return 'Extended Notifications';
    }

    public function getDescription()
    {
        return 'Enable desktop and sound notifications';
    }
}
