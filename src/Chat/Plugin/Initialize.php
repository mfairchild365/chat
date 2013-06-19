<?php
namespace Chat\Plugin;

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
                $event->addRoute('/^admin\/plugins$/', __NAMESPACE__ . '\EditPlugins');
            }
        );

        $listeners[] = array(
            'event'    => \Chat\Events\NavigationSubCompile::EVENT_NAME,
            'listener' => function (\Chat\Events\NavigationSubCompile $event) {
                $class = get_class($event->getView());

                if (strpos($class, 'Chat\Setting\\')  === 0) {
                    $event->addNavigationItem(\Chat\Config::get('URL') . 'admin/plugins', 'Plugins');
                }
            }
        );

        return $listeners;
    }
}