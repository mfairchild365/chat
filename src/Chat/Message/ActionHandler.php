<?php
namespace Chat\Message;

class ActionHandler implements \LAN\ActionHandlerInterface
{
    public function handle($action, $data, \LAN\ConnectionContainer $editor)
    {

        $message = nl2br(trim(strip_tags($data)));

        if ($message == '') {
            return;
        }

        $object = Record::createNewMessage($editor->getUser()->getID(), $message);

        $returnData           = array();
        $returnData['action'] = 'MESSAGE_NEW';
        $returnData['data']   = $object;

        return $returnData;
    }
}