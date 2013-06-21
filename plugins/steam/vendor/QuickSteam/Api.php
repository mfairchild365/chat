<?php
namespace QuickSteam;

class Api
{
    const USER_API = 'http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/';

    protected $api_key;

    protected function __construct($api_key)
    {
        $this->api_key = $api_key;
    }

    public static function getService($api_key)
    {
        return new self($api_key);
    }

    protected function getData($url, array $ids)
    {

    }

    public function getUsers(array $ids)
    {
        $ids = implode(',', $ids);

        $url = self::USER_API . '?key=' . $this->api_key . '&steamids=' . $ids;

        $users = array();

        if (!$data = json_decode(file_get_contents($url))) {
            //failed, return an empty object.
            return $users;
        }

        if (!isset($data->response->players)) {
            //failed, return an empty object.
            return $users;
        }

        foreach ($data->response->players as $key=>$user_data) {
            $users[$key] = new User;
            $users[$key]->syncWithData($user_data);
        }

        return $users;
    }

    public function getUser($id)
    {
        $users = $this->getUsers(array($id));

        return reset($users);
    }
}