<?php
namespace Chat\User;

class RequiredLoginException extends \Exception
{
    public function __construct() {
        parent::__construct("You must be logged in to view this", 401);
    }
}