<?php
namespace Chat\User;

use Chat\PostHandlerInterface;

class Edit extends User implements PostHandlerInterface
{
    function __construct($options = array())
    {
        if (isset($options['id']) && $object = self::getByID($options['id'])) {
            $this->synchronizeWithArray($object->toArray());
        }

        if (!$user = Service::getCurrentUser()) {
            throw new RequiredLoginException();
        }

        if ($user->role != 'ADMIN' && $user->id != $this->id) {
            throw new NotAuthorizedException();
        }
    }

    function handlePost($get, $post, $files)
    {
        print_r($post); exit();
    }
}