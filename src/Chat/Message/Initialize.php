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

        //Handle 'GET_CHAT_MESSAGES'
        $listeners[] = array(
            'event'    => \Chat\WebSocket\Events\OnMessage::EVENT_NAME,
            'listener' => function (\Chat\WebSocket\Events\OnMessage $event) {
                if ($event->getAction() != 'GET_CHAT_MESSAGES') {
                    return;
                }

                $data= $event->getData();

                if (!isset($data['before_message_id'])) {
                    //Get the latest set of messages
                    $messages = RecordList::getLatestMessages();
                } else {
                    //get messages before the given id
                    $messages = RecordList::getMessagesOlderThanID($data['before_message_id']);
                }

                $timeRequested = time();

                foreach ($messages as $message) {
                    $message->message = \Chat\Util::makeClickableLinks($message->message);
                    $message->time_requested = $timeRequested;
                    \Chat\WebSocket\Application::sendToAll('MESSAGE_NEW', $message);
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

                $object->message = \Chat\Util::makeClickableLinks($object->message);

                \Chat\WebSocket\Application::sendToAll('MESSAGE_NEW', $object);
            }
        );

        return $listeners;
    }
}