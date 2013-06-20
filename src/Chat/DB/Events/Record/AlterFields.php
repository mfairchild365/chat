<?php
namespace Chat\DB\Events\Record;

class AlterFields extends \Symfony\Component\EventDispatcher\Event
{
    const EVENT_NAME = 'db.record.alter.fields';

    public $fields;
    protected $record;

    public function __construct(\Chat\Record $record, array $fields)
    {
        $this->record = $record;
        $this->fields = $fields;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function setFields(array $fields)
    {
        $this->fields = $fields;
    }

    public function getRecord()
    {
        return $this->record;
    }
}