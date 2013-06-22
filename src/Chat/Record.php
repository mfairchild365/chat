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
        if (!in_array($var, array_keys($this->getFields()))) {
            throw new \Chat\Exception("Trying to set the value of a non-existent field");
        }

        $this->$var = $value;
    }
}