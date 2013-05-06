<?php
namespace Chat\Setting;

use Chat\PostHandlerInterface;

class Edit extends Setting implements PostHandlerInterface
{
    function __construct($options = array())
    {

    }

    function handlePost($get, $post, $files)
    {
        print_r($post); exit();
    }
}