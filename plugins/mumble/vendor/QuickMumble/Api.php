<?php
namespace QuickMumble;

class Api
{
    protected $api_url = false;

    protected function __construct($api_url)
    {
        $this->api_url = $api_url;
    }

    public static function getService($api_url)
    {
        return new self($api_url);
    }

    public function getUsers()
    {
        $users = array();

        if (!$data = json_decode(@file_get_contents($this->api_url))) {
            //failed, return an empty object.
            return $users;
        }

        return $this->getUsersFromChannel($data->root);
    }

    protected function getUsersFromChannel($channel) {
        $users = array();

        foreach ($channel->users as $userInfo) {
            $userInfo->channelName = $channel->name;
            $user = new User();
            $user->syncWithData((array)$userInfo);
            $users[] = $user;
        }

        foreach ($channel->channels as $newChannel) {
            $users = array_merge($users, $this->getUsersFromChannel($newChannel));
        }

        return $users;
    }

    public function getServer()
    {
        $server = new Server();
        if (!$data = json_decode(@file_get_contents($this->api_url))) {
            //failed, return an empty object.
            $server->x_status = 'offline';
            return $server;
        }

        unset($data->root);
        $server->syncWithData((array)$data);
        $server->x_status = 'online';

        return $server;
    }
}