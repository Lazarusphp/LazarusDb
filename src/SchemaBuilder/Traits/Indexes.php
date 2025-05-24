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


    public function primaryKey(array | string $columns= "")
    {

      
        

        if(is_array($columns) && count($columns) >= 1)
        {
            foreach($columns as $column)
            {
                if(!$this->keyExists($column, self::$primaryKey, "Primary key column $column already exists"))
                {
                    self::$primaryKey[$column] = $column;
                }
                else
                {
                    trigger_error("Primary key column $column already exists");
                    exit();
                }
            }
        }
        elseif(is_string($columns) && !empty($columns))
        {
            if(!$this->keyExists($columns, self::$primaryKey, "Primary key column $columns already exists"))
            {
                self::$primaryKey[$columns] = $columns;
            }
            else
            {
                trigger_error("Primary key column $columns already exists");
                exit();
            }
        }
        else
        {
            if(!$this->keyExists($this->name, self::$primaryKey, "Primary key column $this->name already exists"))
            {
                self::$primaryKey[$this->name] = $this->name;
            }
            else
            {
                trigger_error("Primary key column $this->name already exists");
                exit();
            }
            return $this;
        }
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