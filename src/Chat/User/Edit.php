<?php
namespace Chat\User;

class Edit extends Record
{
    function __construct($options = array())
    {
        if (isset($options['id']) && $object = self::getByID($options['id'])) {
            $this->synchronizeWithArray($object->toArray());
        }
    }

    function handlePost($get, $post, $files)
    {
        print_r($post); exit();
    }
}