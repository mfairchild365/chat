<?php
namespace Chat\Plugins\Gravatar;

class Plugin implements \Chat\Plugin\PluginInterface
{
    public function onInstall()
    {

    }

    public function onUninstall()
    {

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