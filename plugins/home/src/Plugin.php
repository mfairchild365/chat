<?php
namespace Chat\Plugins\Home;

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
        return 'Example Home Plugin';
    }

    public function getDescription()
    {
        return 'Example plugin that changes the home page.';
    }
}
