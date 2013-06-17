<?php
namespace Chat\Chat;

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
            'event'    => \Chat\Events\RoutesCompile::EVENT_NAME,
            'listener' => function (\Chat\Events\RoutesCompile $event) {
                $event->addRoute('/^$/', __NAMESPACE__ . '\View');
            }
        );

        $listeners[] = array(
            'event'    => \Chat\Events\NavigationMainCompile::EVENT_NAME,
            'listener' => function (\Chat\Events\NavigationMainCompile $event) {
                $event->addNavigationItem(\Chat\Config::get('URL'), 'Home');
            }
        );

        $listeners[] = array(
            'event'    => \Chat\Events\JavascriptCompile::EVENT_NAME,
            'listener' => function (\Chat\Events\JavascriptCompile $event) {
                if (get_class($event->getView()) == 'Chat\Chat\View') {
                    $event->addScript(\Chat\Config::get('URL') . 'www/templates/html/js/chat.js');
                }

            }
        );

        return $listeners;
    }
}