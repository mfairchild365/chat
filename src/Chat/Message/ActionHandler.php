<?php
namespace Chat\Message;

class ActionHandler implements \Chat\WebSocket\ActionHandlerInterface
{
    public function handle($action, $data, \Chat\WebSocket\ConnectionContainer $editor)
    {

        $message = nl2br(trim(strip_tags($data)));

        if ($message == '') {
            return;
        }

        $object = Message::createNewMessage($editor->getUser()->id, $message);

        $returnData           = array();
        $returnData['action'] = 'MESSAGE_NEW';
        $returnData['data']   = $object;

        return $returnData;
    }
}