<?php
namespace Chat\Setting;

class Settings extends \DB\RecordList
{
    public function getDefaultOptions()
    {
        $options = array();
        $options['itemClass'] = '\Chat\Setting\Setting';
        $options['listClass'] = __CLASS__;

        return $options;
    }

    public static function getAllSettings($options = array())
    {
        //Build the list
        $options['sql'] = "SELECT id
                           FROM settings
                           ORDER BY setting_name ASC";

        return new self($options);
    }
}