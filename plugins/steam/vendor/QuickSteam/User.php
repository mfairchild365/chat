<?php
namespace QuickSteam;

class User extends Record
{
    const BASE_API_URL = 'http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/';

    //public data
    public $steamid;
    public $personaname;
    public $profileurl;
    public $avatar;
    public $avatarmedium;
    public $avatarfull;
    public $personastate;
    public $communityvisibilitystate;
    public $profilestate;
    public $lastlogoff;
    public $commentpermission;

    //private data
    public $realname;
    public $primaryclanid;
    public $timecreated;
    public $gameid;
    public $gameserverip;
    public $gameextrainfo;
    public $cityid;
    public $loccountrycode;
    public $locstatecode;
    public $loccityid;
}