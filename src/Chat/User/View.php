<?php
namespace Chat\User;

class View extends User
{
    function __construct($options = array())
    {
        if (isset($options['id']) && $object = self::getByID($options['id'])) {
            $this->synchronizeWithArray($object->toArray());
        }
    }
}