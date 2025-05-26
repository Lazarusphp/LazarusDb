<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder\Traits;
use LazarusPhp\LazarusDb\SchemaBuilder\Schema;
use LazarusPhp\LazarusDb\SharedAssets\Traits\ArrayControl;

trait Indexes
{
    public static $primaryKey = [];
    public static $index = [];
    public static $unique = [];

    public function ai($name)
    {
       $this->query[$name] .= " AUTO_INCREMENT ";
       return $this;
    }

    public function getPrimaryKey()
    {   
        // Check if the pk has been set in primary key and if not default to id;
        $pk = isset(self::$primaryKey["pk"]) ? self::$primaryKey["pk"] : "id";
        //Set the primary key based on $pk
        $this->query[$pk] .= " AUTO_INCREMENT ";
        // Return the results;
        $this->query["pk"] = "PRIMARY KEY (".$pk.") ";
        return;
    }


    // Changed name from primaryKey to primary
    public function primary()
    {  
        // set the primary key 
        self::$primaryKey["pk"] = $this->name;
    }

    public function index()
    {
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

    public function getIndexes()
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

        public function getUniques()
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
        self::$unique[$this->name] = $this->name;
        return $this;
    }
}