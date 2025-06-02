<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder\Traits;
use LazarusPhp\LazarusDb\SchemaBuilder\CoreFiles\SchemaCore;
use LazarusPhp\LazarusDb\SchemaBuilder\Schema;

trait Datatypes
{

    private $datatype = [];
    // Type will be used to reference the type  ie int varchar 
    protected $type = [];

    private function ProcessRequest($name, $command)
    {
        // Define new Array#
        $table = Schema::getTable();
        $this->name = $name;
        // Set a new datatype if it doesnt exist;
        if (!isset($this->datatype[$table])) {
            $this->datatype[$table] = [];
            $this->type[$table] = [];
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
        $table = Schema::getTable();
        $this->type[$table][$name] = "char";
        $this->ProcessRequest($name,"  VARCHAR($value) ");
        return $this;
    }

    public function varchar($name,$value=24)
    {   $this->type[$table][$name] = "varchar";
        $this->ProcessRequest($name,"  VARCHAR($value) ");
        return $this;
    }

    public function tinyint($name)
    {
        $this->type[$table][$name] = "tinyint";
        $this->ProcessRequest($name," TINYINT(1) ");
        return $this;
    }

    public function int($name)
    {$this->type[$table][$name] = "int";
        $this->ProcessRequest($name," INT ");
        return $this;
    }


    public function bigint($name)
    {
        $this->type[$table][$name] = "bigint";
        $this->ProcessRequest($name," BIGINT ");
        return $this;
    }
    

    public function text($name)
    {
        $this->type[$table][$name] = "text";
        $this->ProcessRequest($name," TEXT ");
        return $this;
    }

    public function mediumText($name)
    {$this->type[$table][$name] = "mediumtext";
        $this->ProcessRequest($name," MEDIUMTEXT ");
        return $this;
    }

    public function longText($name)
    {$this->type[$table][$name] = "longtext";
        $this->ProcessRequest($name," LongText ");
        return $this;
    }

    public function date($name)
    {
        $this->type[$table][$name] = "date";
        $this->ProcessRequest($name," DATE ");
        return $this;
    }

    public function dateTime($name)
    {
        $this->type[$table][$name] = "datetime";
        $this->ProcessRequest($name," DATETIME ");
        return $this;
    }


}