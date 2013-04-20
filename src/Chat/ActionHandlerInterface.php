<?php
namespace Chat;

interface ActionHandlerInterface
{
    /*
     * Should handle a web socket action
     *
     * @return mixed, (array['action'] and array['data'] defined to send a message to everyone, otherwise null.
     */
    public function handle($action, $data, \LAN\ConnectionContainer $editor);
}