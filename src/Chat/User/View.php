<?php
namespace Chat\User;

class View extends User implements \Chat\ViewableInterface
{
    function __construct($options = array())
    {
        if (isset($options['id']) && $object = self::getByID($options['id'])) {
            $this->synchronizeWithArray($object->toArray());
        }
    }

    public function getPageTitle()
    {
        return "User: " . $this->username;
    }
}