<?php
namespace Chat\Plugins\Mumble;

class Edit implements \Chat\ViewableInterface, \Chat\PostHandlerInterface
{
    protected $options = array();

    function __construct($options = array())
    {
        //User must be logged in
        if (!$user = \Chat\User\Service::getCurrentUser()) {
            throw new \Chat\User\RequiredLoginException();
        }

        $this->options = $options;
    }

    public function getPageTitle()
    {
        return "Edit Mumble Info";
    }

    public function getEditURL()
    {
        return \Chat\Config::get('URL') . 'users/' . $this->options['users_id'] . '/edit/mumble';
    }

    public function getURL()
    {
        return \Chat\Config::get('URL') . 'users/' . $this->options['users_id'] . '/edit/mumble';
    }

    public function handlePost($get, $post, $files)
    {
        if (!isset($post['mumble_name'])) {
            throw new \Chat\Exception("You must provide the mumble name", 400);
        }

        $user = \Chat\User\User::getByID($this->options['users_id']);
        $user->mumble_name = $post['mumble_name'];
        $user->save();

        \Chat\Controller::redirect(
            $this->getURL(),
            new \Chat\FlashBagMessage("success", "User profile update successful!")
        );
    }
}