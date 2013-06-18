<?php
namespace Chat\Plugins\Notifications;

class Plugin extends \Chat\Plugin\PluginInterface
{
    public function onInstall()
    {

    }

    public function onUninstall()
    {

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
