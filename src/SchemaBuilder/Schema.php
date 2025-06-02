<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder;
use LazarusPhp\LazarusDb\SchemaBuilder\CoreFiles\SchemaCore;
use LazarusPhp\LazarusDb\SchemaBuilder\Table;
use LazarusPhp\LazarusDb\SharedAssets\Traits\TableControl;

class Schema extends SchemaCore
{
    use TableControl;
    protected static $function;

    public static function getMethod()
    {
        return self::$function;
    }
    public static function table($table)
    {
        self::$table = $table;
    
        return new static;
    }

    public static function MigrationFailed()
    {
        if(isset(self::$migrationFailed[self::$table]) && self::$migrationFailed[self::$table] === true)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

        public static  function getTable()
    {
        return self::$table;
    }


    public function create(callable $table)
    {    
        self::$function = __FUNCTION__;
        self::$sql = "CREATE TABLE IF NOT EXISTS " . self::$table . " (";
        if(is_callable($table))
        {
            $class = new Table();
            $table($class);
            self::$sql .= $class->build();
        }
        self::$sql .= ")";
        echo self::$sql;
        !$this->save() ? self::$migrationFailed = true : self::$migrationFailed = false;
     
        dd(self::$migrationError);
    }

    public function alter(callable $table)
    {
        self::$function = __FUNCTION__;
        self::$sql = "ALTER TABLE " . self::$table . " ";
        if(is_callable($table))
        {
            $class = new Table();
            $table($class);
            self::$sql .= $class->build();
        }
        echo self::$sql;
        !$this->save() ? self::$migrationFailed = true : self::$migrationFailed = false;
        dd(self::$migrationError);
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