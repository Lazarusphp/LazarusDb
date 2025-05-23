<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder\Traits;

trait Indexes
{

    public static $primaryKey = [];

    public function ai()
    {
       $this->query[$this->name] .= " AUTO_INCREMENT ";
       return $this;
    }

    public function getPrimaryKey()
    {
    
        if(!array_key_exists("pk",$this->query)){
        if(count(self::$primaryKey) >= 1)
        {
            $pk = implode(", ",self::$primaryKey);
        }
         $this->query["pk"] = " PRIMARY KEY ($pk)";
        }
        else
        {
            trigger_error("Cannot find primary key please create one");
        }
    }


    public function primaryKey()
    {
        self::$primaryKey[$this->name] = $this->name;
        return $this;
    }

    // public function index(string $name)
    // {
    //     $this->query .= "INDEX ($name) ";
    //     return $this;
    // }

    // public function unique($name)
    // {
    //     $this->query .= "UNIQUE ($name) ";
    //     return $this;
    // }
}