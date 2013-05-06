<?php
namespace Chat\Setting;

use Chat\PostHandlerInterface;

class Edit extends Setting implements PostHandlerInterface
{
    function __construct($options = array())
    {
        if (!$user = \Chat\User\Service::getCurrentUser()) {
            throw new \Chat\User\RequiredLoginException();
        }

        if ($user->role != 'ADMIN') {
            throw new \Chat\User\NotAuthorizedException();
        }
    }

    function handlePost($get, $post, $files)
    {
        if (!isset($post['settings'])) {
            throw new \Exception("Settings array does not exist", 400);
        }

        foreach ($post['settings'] as $id=>$value)
        {
            if (!$setting = Setting::getByID($id)) {
                throw new \Exception("Could not find setting with ID: $id", 400);
            }

            $setting->setting_value = $value;
            $setting->save();
        }

        \Chat\Controller::redirect(
            \Chat\Config::get('URL') . "admin",
            new \Chat\FlashBagMessage("success", "Settings have been saved")
        );
    }
}