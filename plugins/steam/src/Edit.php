<?php
namespace Chat\Plugins\Steam;

class Edit implements \Chat\ViewableInterface
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
        return "Edit Steam Info";
    }

    public function getEditURL()
    {
        return \Chat\Config::get('URL') . 'login/steam';
    }

    public function getURL()
    {
        return \Chat\Config::get('URL') . 'users/' . $this->options['users_id'] . '/edit/steam';
    }
}