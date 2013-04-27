<?php
namespace Chat;

interface PostHandlerInterface
{
    public function handlePost($get, $post, $files);
}