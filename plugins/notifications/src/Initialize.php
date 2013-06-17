<?php
namespace Chat\Plugins\Notifications;

class Initialize implements \Chat\Plugin\InitializePluginInterface
{
    public $options = array();

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
            'event'    => \Chat\Events\JavascriptCompile::EVENT_NAME,
            'listener' => function (\Chat\Events\JavascriptCompile $event) {
                //Add notifications to every page
                $event->addScript(\Chat\Config::get('URL') . 'plugins/notifications/www/templates/html/js/notifications.js');
            }
        );

        return $listeners;
    }

}