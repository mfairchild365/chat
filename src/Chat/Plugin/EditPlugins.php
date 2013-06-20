<?php
namespace Chat\Plugin;

class EditPlugins implements \Chat\PostHandlerInterface, \Chat\ViewableInterface
{
    public function __construct($options = array())
    {
        if (!$user = \Chat\User\Service::getCurrentUser()) {
            throw new \Chat\User\RequiredLoginException();
        }

        if ($user->role != 'ADMIN') {
            throw new \Chat\User\NotAuthorizedException();
        }
    }

    public function handlePost($get, $post, $files)
    {
        if (!isset($post['enabled_plugins'])) {
            $post['enabled_plugins'] = array();
        }

        //Find out which ones we need to install, and install them.
        foreach ($post['enabled_plugins'] as $name) {
            $info = PluginManager::getManager()->getPluginInfo($name);

            //Skip because it is already installed.
            if ($info->isInstalled()) {
                continue;
            }

            if ($info->install()) {
                \Chat\Controller::addFlashBagMessage(new \Chat\FlashBagMessage('success', $info->getName() . ' was installed'));
            } else {
                \Chat\Controller::addFlashBagMessage(new \Chat\FlashBagMessage('error', 'There was an error installing ' . $info->getName()));
            }
        }

        //Uninstall plugins
        foreach (PluginList::getAllPlugins() as $plugin) {
            if (!in_array($plugin->name, $post['enabled_plugins'])) {
                $info = PluginManager::getManager()->getPluginInfo($plugin->name);
                if ($info->uninstall()) {
                    \Chat\Controller::addFlashBagMessage(new \Chat\FlashBagMessage('success', $info->getName() . ' was uninstalled'));
                } else {
                    \Chat\Controller::addFlashBagMessage(new \Chat\FlashBagMessage('error', 'There was an error uninstalling ' . $info->getName()));
                }
            }
        }

        \Chat\Controller::redirect(
            $this->getEditURL(),
            new \Chat\FlashBagMessage('success',  'Finished Updating Plugins')
        );
    }

    public function getPageTitle()
    {
        return 'Manage Plugins';
    }

    public function getURL()
    {
        return $this->getEditURL();
    }

    public function getEditURL()
    {
        return \Chat\Config::get('URL') . 'admin/plugins';
    }
}