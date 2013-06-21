<?php
namespace Chat\Plugins\Steam;

class Login implements \Chat\ViewableInterface
{
    const STEAM_AUTH_URL = "http://steamcommunity.com/openid";

    function __construct($options = array())
    {
        //User must be logged in
        if (!$user = \Chat\User\Service::getCurrentUser()) {
            throw new \Chat\User\RequiredLoginException();
        }

        $this->authenticate();
    }

    public function getPageTitle()
    {
        return "Steam Login";
    }

    public function getURL()
    {
        return \Chat\Config::get('URL') . 'login/steam';
    }

    public function authenticate()
    {
        $openid = new \LightOpenID($this->getURL());

        //check if we need to send the user to steam.
        if(!$openid->mode) {
            $openid->identity = self::STEAM_AUTH_URL;
            header('Location: ' . $openid->authUrl());
            exit();
        }

        if($openid->mode == 'cancel') {
            throw new \Chat\Exception('User has canceled authentication!', 400);
        }

        if (!$openid->validate()) {
            throw new \Chat\Exception('You have chosen not to identify yourself.  Please try again if you feel this was an error.', 400);
        }

        $id = substr($openid->identity, 36);

        $user = \Chat\User\Service::getCurrentUser();

        $user->steam_id_64 = $id;
        $user->save();

        \Chat\Controller::redirect(
            \Chat\Config::get("URL") . 'users/' . $user->id . '/edit/steam',
            new \Chat\FlashBagMessage("success", "User profile update successful!")
        );
    }
}