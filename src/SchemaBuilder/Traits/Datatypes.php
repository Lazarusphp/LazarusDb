<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder\Traits;
use LazarusPhp\LazarusDb\SchemaBuilder\CoreFiles\SchemaCore;
use LazarusPhp\LazarusDb\SchemaBuilder\Schema;

trait Datatypes
{

    private $datatype = [];

    private function ProcessRequest($name, $command)
    {
        // Define new Array#
        $table = Schema::getTable();
        $this->name = $name;
        // Set a new datatype if it doesnt exist;
        if (!isset($this->datatype[$table])) {
            $this->datatype[$table] = [];
        }
        
        if (!array_key_exists($this->name, $this->datatype[$table])) {
           $this->datatype[$table][$this->name] = $this->name . $command;
            return true;
            
        } else {
            Schema::$migrationError[$table] = "Cannot use Duplicate $name on table $table";
            Schema::$migrationFailed[$table] = true;
            return false;
        }
    }


    public function string($name,$value=24)
    {
        $this->ProcessRequest($name,"  VARCHAR($value) ");
        return $this;
    }

    public function varchar($name,$value=24)
    {
        $this->ProcessRequest($name,"  VARCHAR($value) ");
        return $this;
    }

    public function tinyint($name)
    {
        $this->ProcessRequest($name," TINYINT(1) ");
        return $this;
    }

    public function int($name)
    {
        $this->ProcessRequest($name," INT ");
        return $this;
    }


    public function bigint($name)
    {
        $this->ProcessRequest($name," BIGINT ");
        return $this;
    }
    

    public function text($name)
    {
        $this->ProcessRequest($name," TEXT ");
        return $this;
    }

    public function mediumText($name)
    {
        $this->ProcessRequest($name," MEDIUMTEXT ");
        return $this;
    }

    public function longText($name)
    {
        $this->ProcessRequest($name," LongText ");
        return $this;
    }

    public function date($name)
    {
        $this->ProcessRequest($name," DATE ");
        return $this;
    }

    public function dateTime($name)
    {
        $this->ProcessRequest($name," DATETIME ");
        return $this;
    }


}