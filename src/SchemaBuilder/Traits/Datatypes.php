<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder\Traits;

use LazarusPhp\LazarusDb\SchemaBuilder\CoreFiles\SchemaCore;
use LazarusPhp\LazarusDb\SchemaBuilder\Schema;
use LazarusPhp\LazarusDb\SchemaBuilder\SchemaActions;
use LazarusPhp\LazarusDb\SchemaBuilder\SchemaValidator;
use LazarusPhp\LazarusDb\SchemaBuilder\Table;
use LazarusPhp\LazarusDb\TableManagement\CoreFiles\TableCore;
use LazarusPhp\LazarusDb\TableManagement\TableControl;

trait Datatypes
{

    private function sendRequest($name, $type, $actions)
    {
        $this->processRequest($name, $type, $actions);
    }

    private $type = "datatype";

    /**
     * nameMatch
     *
     * @param [type] $name
     * @description "matches column name with Database Information Schema"
     * @return true|false
     *
     * 
     */

    protected static function hasDataType(string $name)
    {
        $params = self::getParams();
        
        foreach($params as $props)
        {
            if(isset($props["datatype"]))
            {
                foreach($props["datatype"] as $value)
                {
                    if($name === $value)
                    {
                        echo "name Found $name";
                        break;
                    }
                }
            } 
        }
    }


    public function id($id = "id")
    {
        return $this->int($id)->ai();
    }

    public function string($name, $value = 100)
    {
        $actions = [
            "function" => __FUNCTION__,
            "name" => $name,
            "value" => $value,
            "command" => "$name CHAR($value)",
        ];

        $this->sendRequest($name, $this->type, $actions);
        return $this;
    }

    public function varchar($name, $value = 255)
    {


        $actions = [
            "function" => __FUNCTION__,
            "name"=>$name,
            "value" => $value,
            "command" => "$name VARCHAR($value)",
        ];

        $this->sendRequest($name, $this->type, $actions);
        return $this;
    }

    public function tinyint($name)
    {
        $actions = [
            "function" => __FUNCTION__,
            "name"=>$name,
            "command" => "$name TINYINT ",
        ];

        $this->sendRequest($name, $this->type, $actions);
        return $this;
    }

    public function int($name)
    {

        $actions = [
            "function" => __FUNCTION__,
            "name"=>$name,
            "command" => "$name INT ",
        ];

        $this->sendRequest($name, $this->type, $actions);
        return $this;
    }


    public function bigint($name)
    {
        $actions = [
            "function" => __FUNCTION__,
            "name" => $name,
            "command" => "$name BIGINT ",
        ];

        $this->sendRequest($name, $this->type, $actions);
        return $this;
    }


    public function text($name)
    {

        $actions = [
            "function" => __FUNCTION__,
            "name" => $name,
            "command" => "$name TEXT ",
        ];

        $this->sendRequest($name, $this->type, $actions);
        return $this;
    }

    public function mediumText($name)
    {

        $actions = [
            "function" => __FUNCTION__,
            "name" => $name,
            "command" => "$name MEDIUMTEXT ",
        ];

        $this->sendRequest($name, $this->type, $actions);
        return $this;
    }

    public function longText($name)
    {

        $actions = [
            "function" => __FUNCTION__,
            "name" => $name,
            "command" => "$name LONGTEXT ",
        ];

        $this->sendRequest($name, $this->type, $actions);
        return $this;
    }

    public function date($name)
    {
        $actions = [
            "function" => __FUNCTION__,
            "name" => $name,
            "command" => "$name DATE ",
        ];

        $this->sendRequest($name, $this->type, $actions);
        return $this;
    }

    public function dateTime($name)
    {

        $actions = [
            "function" => __FUNCTION__,
            "name" => $name,
            "command" => "$name DATETIME ",
        ];

        $this->sendRequest($name, $this->type, $actions);
        return $this;
    }
}
