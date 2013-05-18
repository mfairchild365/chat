<?php
namespace Chat\Plugins\Gravatar;

class Initialize
{
    public $options = array();

    public function __construct($options = array())
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
                //Add gravatar to every page
                $event->addScript(\Chat\Config::get('URL') . 'plugins/gravatar/www/templates/html/js/gravatar.js');
                $event->addScript(\Chat\Config::get('URL') . 'plugins/gravatar/www/templates/html/js/md5-min.js');
            }
        );

        return $listeners;
    }

}