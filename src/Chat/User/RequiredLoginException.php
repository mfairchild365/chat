<?php
namespace Chat\User;

class RequiredLoginException extends \Exception implements \Chat\ViewableInterface
{
    public function __construct() {
        parent::__construct("You must be logged in to view this", 401);
    }

    public function getPageTitle()
    {
        return "Error";
    }

    public function getURL()
    {
        return "";
    }
}