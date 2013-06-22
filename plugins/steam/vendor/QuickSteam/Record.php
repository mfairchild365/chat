<?php
namespace QuickSteam;

class Record
{
    public function syncWithData($data)
    {
        if (is_object($data)) {
            $data = (array)$data;
        }

        foreach ($data as $key=>$value) {
            $this->$key = $value;
        }
    }
}