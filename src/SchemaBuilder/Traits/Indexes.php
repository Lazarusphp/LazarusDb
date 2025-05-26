<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder\Traits;
use LazarusPhp\LazarusDb\SchemaBuilder\Schema;
use LazarusPhp\LazarusDb\SharedAssets\Traits\ArrayControl;

trait Indexes
{
    protected static $primaryKey = [];
    protected static $index = [];
    protected static $unique = [];
    protected $ai = true;


    public function AutoIncrement($ai=true)
    {
        $this->ai = ($ai === true)  ? true : false;
        return $this;
    }

        private function setAutoIncrement($name)
    {
        $supported = ["int", "bigint", "tinyint"];
        if(in_array($this->datatype[$name][0], $supported))
        {
            return " AUTO_INCREMENT ";
        }
        else{
            Schema::$migrationError[] = "Auto Increment is not supported for ".$this->datatype[$name][0];
            $this->buildFailed = true;
            return "";
        }   
       
    }

    public function loadPrimaryKey()
    {   
        // Check if the pk has been set in primary key and if not default to id;
        $id = isset(self::$primaryKey["pk"]) ? self::$primaryKey["pk"] : "id";
        //Set the primary key based on $pk
        $this->query[$id] .= ($this->ai===true) ?  $this->setAutoIncrement($id) : " ";
        // Return the results;
        $this->query["pk"] = "PRIMARY KEY ($id) ";
        return;
    }


    // Changed name from primaryKey to primary
    public function primary()
    {  
        if(self::keyExists("pk", self::$primaryKey))
        {
            Schema::$migrationError[] = "Primary key has already been set at column ".self::$primaryKey["pk"];
            $this->buildFailed = true;
            return;
        }
        else{
        $this->datatype[$this->name][] = "primary";
        // $this->ai = $ai;
        $unsupported = ["default"];
        foreach($this->datatype as $key => $value)
{
    foreach($unsupported as $un)
    {
        // $value is an array, so check each element
        foreach ($value as $v) {
            if(strpos($v, $un) !== false)
            {
                Schema::$migrationError[] = "Primary key cannot be set with $un";
                $this->buildFailed = true;
            }
        }
    }
}
        // set the primary key 
        self::$primaryKey["pk"] = $this->name;
        return $this;
     }
    }

    public function index()
    {
        $this->datatype[$this->name][] .= "index";
        if(self::keyExists($this->name,self::$index))
        {
            Schema::$migrationError[] = "Duplicate index given";
            $this->buildFailed = true;
        }
        else{
        self::$index[$this->name] = $this->name;
        return $this;
        }
    }

    public function loadIndexes()
    {
        if(count(self::$index) >= 1){
        $columns = [];
        foreach(self::$index as $key => $index)
        {
            $columns[] = $index;
        }
        
        $this->query["indexes"] =  "INDEX indexes (".implode(',',$columns).") ";
        }
    }

        public function loadUniques()
    {
        if(count(self::$unique) >= 1){
            if(self::keyExists($this->name,self::$index))
        {
            Schema::$migrationError[] = "Duplicate index given"; 
            $this->buildFailed = true;
        }
        else{
        $columns = [];
        foreach(self::$unique as $key => $unique)
        {
    
            $column[] = $unique;
        }

        $this->query["unique"] = "CONSTRAINT unique_keys  UNIQUE (".implode(',', $column).") ";
        }
        }
    }

    public function unique()
    {
        $this->datatype[$this->name][] .= "unique";
        self::$unique[$this->name] = $this->name;
        return $this;
    }
}