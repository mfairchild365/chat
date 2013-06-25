<?php
namespace QuickMumble;

class User extends Record
{
    //public data
    public $comment;
    public $mute;
    public $suppress;
    public $selfDeaf;
    public $deaf;
    public $selfMute;
    public $bytespersec;
    public $session;
    public $idlesecs;
    public $identify;
    public $name;
    public $userid;
    public $onlinesecs;
    public $channel;
    public $prioritySpeaker;

    //gnerated data
    public $channelName;
}