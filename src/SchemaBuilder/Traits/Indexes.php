<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder\Traits;

use LazarusPhp\LazarusDb\SchemaBuilder\Schema;
use LazarusPhp\LazarusDb\SharedAssets\Traits\ArrayControl;

trait Indexes
{
    protected static $primaryKey = [];
    protected static $index = [];
    protected static $indexKey = [];
    protected static $indexType = []; // index or unique
    protected $ai = [];

    private function processAi()
    {
        $table = Schema::getTable();
        if(!isset($this->ai[$table]))
        {
            $this->ai[$table] = [];
        }

            if(array_key_exists($this->name,$this->ai[$table]))
            {
                  Schema::$migrationError[$table] = "Cannot Apply Auto increment  to the same column : {$this->name}";
                    Schema::$migrationFailed[$table] = true;
                return false;
            }
            
            if(count($this->ai[$table]) > 1)
            {
                Schema::$migrationError[$table] = "Cannot apply Auto increment to multiple colums";
                Schema::$migrationFailed[$table] = true;
                return false;
            }
            else{
             $this->ai[$table][$this->name] = " AUTO_INCREMENT ";
            
            }
            
    }

    public function processIndexes()
    {
        $table = Schema::getTable();
        
        if(isset(self::$index[$table]))
        {  
            if(count(self::$index[$table]) > 1)
            {
                foreach(self::$indexType[$table] as $value => $type)
                {
                    if($type === "index")
                    {
                        $this->loadIndexes();
                    }
                    
                    if($type === "unique")
                    {
                        $this->loadUniques();
                    }
                }
            }
        }
    }
    
  


    public function ai()
    {
        $this->processAi();
        return $this;
    }

    public function loadPrimaryKey()
    {   
         $table = Schema::getTable();
        // Check if the pk has been set in primary key and if not default to id;
        $id = isset(self::$primaryKey[$table]["pk"]) ? self::$primaryKey[$table]["pk"] : "id";
        $this->query["pk"] = " PRIMARY KEY ($id) ";
        
    }


    // Changed name from primaryKey to primary
    public function primary()
    {
        // set the primary key      
        $table = Schema::getTable();
        if(!isset(self::$primaryKey[$table]))
        {
            self::$primaryKey[$table] = [];
        }

        self::$primaryKey[$table]["pk"] = $this->name;
        return $this;
    }

    public function index($key = "idx_default")
    {
        $table = Schema::getTable();
        if(!isset(self::$index[$table]))
        {
            self::$index[$table] = [];
            self::$indexKey[$table] = [];
            self::$indexType[$table] = [];
        }
            self::$index[$table][$this->name] = $this->name;
            self::$indexKey[$table][$this->name] = $key;
            self::$indexType[$table][$this->name] = "index";
        return $this;
    }

    private function loadIndexes()
    {
            $table = Schema::getTable();
            foreach (self::$index[$table] as $key => $value) {
                $idx_name = isset(self::$indexKey[$table][$key]) ? "idx_" . self::$indexKey[$table][$key] : "idx_default";
                $columns[] = $value;
            }
            $this->query["indexes"] =  "INDEX $idx_name (" . implode(',', $columns) . ") ";
        
    }

    private function loadUniques()
    {
        $table = Schema::getTable();
            foreach (self::$index[$table] as $key => $unique) {
                $unique_name = isset(self::$indexKey[$key]) ? "unique_" . self::$indexKey[$key] : "unique_default";
                $columns[] = $unique;
            

            $this->query["uniques"] =  "CONSTRAINT UNIQUE $unique_name (" . implode(',', $columns) . ") ";
        }
    }

    public function unique($key="unique_")
    {

            $table = Schema::getTable();
            self::$index[$table][$this->name] = $this->name;
            self::$indexKey[$table][$this->name] = $key;
            self::$indexType[$table][$this->name] = "unique";
            return $this;
    }
}
