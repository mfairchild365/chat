<?php
namespace Chat\User;

use Chat\PostHandlerInterface;

class Logout implements PostHandlerInterface
{
    function handlePost($get, $post, $files)
    {
        //Log out the user.
        Service::logOut();

        //Redirect to the home page
        \Chat\Controller::redirect(\Chat\Config::get("URL"));
    }

    public function getEditURL()
    {
        return \Chat\Config::get("URL") . "logout";
    }
}