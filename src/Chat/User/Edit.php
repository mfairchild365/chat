<?php
namespace Chat\User;

use Chat\PostHandlerInterface;

class Edit extends User implements PostHandlerInterface, \Chat\ViewableInterface
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
        if (!isset($post['email']) || empty($post['email'])) {
            throw new \Exception("Email address can not be empty.", 400);
        }

        if (isset($post['password'], $post['password_verify'])
            && !empty($post['password'])
            && !empty($post['password_verify'])) {

            if ($post['password'] != $post['password_verify']) {
                throw new \Exception("Both passwords must match", 400);
            }

            $post['password'] = Register::hashPassword($post['password']);
        }

        $this->synchronizeWithArray($post);

        $this->save();

        \Chat\Controller::redirect(
            $this->getEditURL(),
            new \Chat\FlashBagMessage("success", "User profile update successful!")
        );
    }

    public function getPageTitle()
    {
        return "User Edit";
    }
}