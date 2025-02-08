<?php
namespace LazarusPhp\DatabaseManager;

class ParamWriter()
{

    private $data = [];

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    public function __unset($name)
    {
        unset($this->data[$name]);
    }

    public functiion save()
    {
        foreach($this->data as $key=>$value)
        {
            echo "$key = $value";
        }
    }
}