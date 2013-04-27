<?php
namespace Chat\Chat;

class View
{
    function __construct($options = array())
    {
        //Require login
        \Chat\User\Service::requireLogin();
    }
}