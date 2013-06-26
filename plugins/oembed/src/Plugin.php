<?php
namespace Chat\Plugins\Oembed;

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
        return 'Oembed - Embed Media';
    }

    public function getDescription()
    {
        return 'Converts posted links into embeded media.  Works for youtube, imgur, etc.';
    }
}
