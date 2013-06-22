<?php
namespace Chat\Message;

class Initialize implements \Chat\Plugin\InitializePluginInterface
{
    public function __construct(array $options)
    {

    }

    public function initialize()
    {

    }

    public function getEventListeners()
    {
        $listeners = array();

        $listeners[] = array(
            'event'    => \Chat\WebSocket\Events\OnOpen::EVENT_NAME,
            'listener' => function (\Chat\WebSocket\Events\OnOpen $event) {
                //Get the user up to date on the conversation
                foreach (\Chat\Message\RecordList::getAllMessages() as $message) {
                    $event->getConnection()->send('MESSAGE_NEW', $message);
                }
            }
        );

        //Handle 'SEND_CHAT_MESSAGE'
        $listeners[] = array(
            'event'    => \Chat\WebSocket\Events\OnMessage::EVENT_NAME,
            'listener' => function (\Chat\WebSocket\Events\OnMessage $event) {
                if ($event->getAction() != 'SEND_CHAT_MESSAGE') {
                    return;
                }

                $message = nl2br(trim(strip_tags($event->getData())));

                if ($message == '') {
                    return;
                }

                $object = Message::createNewMessage($event->getConnection()->getUser()->id, $message);

                $returnData           = array();
                $returnData['action'] = 'MESSAGE_NEW';
                $returnData['data']   = $object;

                \Chat\WebSocket\Application::sendToAll('MESSAGE_NEW', $object);
            }
        );

        return $listeners;
    }
}