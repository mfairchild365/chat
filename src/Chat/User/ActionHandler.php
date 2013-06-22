<?php
namespace Chat\User;

class ActionHandler implements \Chat\WebSocket\ActionHandlerInterface
{
    public function handle($action, $data,  \Chat\WebSocket\ConnectionContainer $editor)
    {
        if (!isset($data['id'])) {
            throw new \Chat\Exception("ID must be passed.");
        }

        $object = User::getByID($data['id']);

        $object->synchronizeWithArray($data);

        $object->save();

        $returnData = array();
        $returnData['action'] = 'USER_UPDATED';
        $returnData['data']   = $object;

        return $returnData;
    }
}