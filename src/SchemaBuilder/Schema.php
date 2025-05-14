<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder;
use LazarusPhp\LazarusDb\CoreFiles\SchemaCore;

class Schema extends SchemaCore
{
    public function __construct(string|array $table)
    {
        parent::__construct();
        // Leaving $table empty will require static instantiation using schema::table("tablename");
        if (!(is_array($table) || is_string($table)))
        {
            trigger_error("Must be an array or string");
        }
        else
        {
            self::$table = is_array($table) ? implode(", ",$table) : $table;
        }
    }

    // Can be called statically with table.
    public static function table(string|array $table)
    {
   
        return new self($table);
        
    }

  

    public function hasTable()
    {
        $query = "SHOW TABLES LIKE '" . self::$table . "'";
        $result = $this->save($query);
        if ($result && $result->rowCount() >= 1) {
           return true;
        }
        else
        {
            return false;
        }
    }




    public function create(callable $table)
    {    
        self::$sql = "CREATE TABLE IF NOT EXISTS " . self::$table . " (";
        if(is_callable($table))
        {
            $class = new Build();
            $table($class);
            self::$sql .= $class->build();
        }

        self::$sql .= ")";
       $result = $this->save();
        return $result ? true : false ;
    }

        public function alter(callable $table)
    {
        self::$sql = "ALTER TABLE " . self::$table;
        if(is_callable($table))
        {
            $class = new AlterTable();
            $table($class);
            self::$sql .= $class->build();
        }
        echo self::$sql;
    $result = $this->save();
    return $result ? true : false;

    }

    // public function index(string|array $column)
    // {
    //     $key = is_array($column) ? implode(",",$column) : "idx_$column";
    //     $column = is_array($column) ? implode(", ",$column) : $column;
    //     self::$sql = "CREATE INDEX $key ON ". self::$table."($column)";
    //     echo "<br>".self::$sql;
    //     $this->save();
    // }

     public function rename($table2)
     {
      self::$sql = "RENAME TABLE " . self::$table . " TO $table2";
      $result = $this->save();
        return $result ? true : false ;

     }

     /**
      * Drop
      *
      * @param array $name
      * Deletes the table completly if it exists;
      * @return void
      */
     public function drop()
     {
        self::$sql = "DROP TABLE IF EXISTS " .self::$table ;
        $this->save();
        
     }

     /**
      * EmptyTable
      * 
      * @param array $table
      * @description Empties table using truncate sql query.
      * @return void
      */
     public function emptyTable()
     {
        self::$sql = "TRUNCATE TABLE ". self::$table ;
        $result = $this->save();
        return $result ? true : false ;
     }
};