<?php
namespace Chat\User;

class Logout
{
    function handlePost($get, $post, $files)
    {
        //Log out the user.
        Service::logOut();

        //Redirect to the home page
        \Chat\Controller::redirect(\Chat\Config::get("URL"));
    }
}