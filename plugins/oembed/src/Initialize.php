<?php
namespace Chat\Plugins\Oembed;

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
            'event'    => \Chat\Events\CSSCompile::EVENT_NAME,
            'listener' => function (\Chat\Events\CSSCompile $event) {
                $view = $event->getView();

                if (get_class($view) != 'Chat\Chat\View') {
                    return;
                }

                $event->addScript(\Chat\Config::get('URL') . 'plugins/mumble/www/templates/html/css/jquery.oembed.css');
            }
        );

        $listeners[] = array(
            'event'    => \Chat\Events\JavascriptCompile::EVENT_NAME,
            'listener' => function (\Chat\Events\JavascriptCompile $event) {
                $view = $event->getView();

                if (get_class($view) != 'Chat\Chat\View') {
                    return;
                }

                $event->addScript(\Chat\Config::get('URL') . 'plugins/oembed/www/templates/html/js/jquery.oembed.js');
                $event->addScript(\Chat\Config::get('URL') . 'plugins/oembed/www/templates/html/js/oembed.js');
            }
        );

        return $listeners;
    }
}