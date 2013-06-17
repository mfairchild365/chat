<?php
namespace Chat;

class PluginList extends \DB\RecordList
{
    public function getDefaultOptions()
    {
        $options = array();
        $options['itemClass'] = '\Chat\Plugin';
        $options['listClass'] = __CLASS__;

        return $options;
    }

    public static function getAllPlugins($options = array())
    {
        //Build the list
        $options['sql'] = "SELECT id
                           FROM plugins";

        return new self($options);
    }
}