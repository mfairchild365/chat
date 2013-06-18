<?php
namespace Chat\Plugin;

abstract class PluginInterface
{
    /**
     * Called when a plugin is installed.  Add sql changes and other logic here.
     *
     * @return mixed
     */
    abstract public function onInstall();

    /**
     * Please undo whatever you did in onInstall().  If you don't, someone might have a bad day.
     *
     * @return mixed
     */
    abstract public function onUnInstall();

    /**
     * Returns the long name of the plugin
     *
     * @return mixed
     */
    abstract public function getName();

    /**
     * Returns a description of the plugin
     *
     * @return mixed
     */
    abstract public function getDescription();

    public function getMachineName()
    {
        return PluginManager::getPluginNameFromClass(get_called_class());
    }

    /**
     * Install this plugin
     *
     * @return bool|mixed
     */
    public function install()
    {
        //is it already installed?
        if ($this->insInstalled()) {
            return false;
        }

        $plugin = new Plugin();
        $plugin->name = $this->getMachineName();

        if (!$plugin->save()) {
            return false;
        }

        return $this->onInstall();
    }

    /**
     * Uninstall this plugin
     *
     * @return bool|mixed
     */
    public function unInstall()
    {
        //is it already unInstalled?
        if (!$plugin = Plugin::getByName($this->getMachineName())) {
            return false;
        }

        if ($plugin->delete()) {
            return false;
        }

        return $this->onUnInstall();
    }

    public function isInstalled()
    {
        if (!$plugin = Plugin::getByName($this->getMachineName())) {
            return false;
        }

        return true;
    }
}