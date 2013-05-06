<?php
namespace Chat\User;

class NotAuthorizedException extends \Exception
{
    public function __construct() {
        parent::__construct("You do not have permission to view this", 402);
    }
}