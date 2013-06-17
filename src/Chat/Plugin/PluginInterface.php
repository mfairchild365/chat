<?php
namespace Chat\Plugin;

interface PluginInterface
{
    /**
     * Called when a plugin is installed.  Add sql changes and other logic here.
     *
     * @return mixed
     */
    public function onInstall();

    /**
     * Please undo whatever you did in onInstall().  If you don't, someone might have a bad day.
     *
     * @return mixed
     */
    public function onUnInstall();
}