<?php
namespace Chat;

abstract class Record extends \DB\Record
{
    function getFields()
    {
        $fields = parent::getFields();

        $result = \Chat\Plugin\PluginManager::getManager()->dispatchEvent(
            \Chat\DB\Events\Record\AlterFields::EVENT_NAME,
            new \Chat\DB\Events\Record\AlterFields($this, $fields)
        );

        return $result->getFields();
    }

    public function &__get($var)
    {
        return $this->$var;
    }

    public function __set($var, $value)
    {
        $this->$var = $value;
    }
}