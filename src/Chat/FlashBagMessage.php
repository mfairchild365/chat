<?php
namespace Chat;

class FlashBagMessage
{
    public $type = "";
    public $message = "";

    public function __construct($type, $message) {
        $this->type = $type;
        $this->message = $message;
    }
}