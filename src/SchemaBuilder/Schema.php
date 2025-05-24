<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder;
use LazarusPhp\LazarusDb\SchemaBuilder\CoreFiles\SchemaCore;
use LazarusPhp\LazarusDb\SharedAssets\Traits\TableControl;

class Schema extends SchemaCore
{
    use TableControl;
    public static $progress = false;

    public static function table($table)
    {
        self::$table = $table;
    
        return new static;
    }
    // public function create(callable $table)
    // {    
    //     self::$sql = "CREATE TABLE IF NOT EXISTS " . self::$table . " (";
    //     if(is_callable($table))
    //     {
    //         $class = new Build();
    //         $table($class);
    //         self::$sql .= $class->build();
    //     }

    //     self::$sql .= ")";
        
    //    $result = $this->save();
    //     return $result ? true : false ;
    // }

    //     public function alter(callable $table)
    // {
    //     self::$sql = "ALTER TABLE " . self::$table;
    //     if(is_callable($table))
    //     {
    //         $class = new AlterTable();
    //         $table($class);
    //         self::$sql .= $class->build();
    //     }
    // $result = $this->save();
    // return $result ? true : false;

    // }

    public function test(callable $table)
    {
        self::$sql = "CREATE TABLE IF NOT EXISTS " . self::$table . " (";
        if(is_callable($table))
        {
            $class = new Table();
            $table($class);
            $class->getPrimaryKey();
            $class->loadFk();
            self::$sql .= $class->build();
        }
        self::$sql .= ")";
        !$this->save() ? self::$migrationFailed = true : self::$migrationFailed = false;
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
        return $this->save() ? true : false ;

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