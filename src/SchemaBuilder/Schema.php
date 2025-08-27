<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder;

use LazarusPhp\LazarusDb\SchemaBuilder\CoreFiles\SchemaCore;
use LazarusPhp\LazarusDb\SchemaBuilder\SchemaActions;
use LazarusPhp\LazarusDb\TableManagement\Table;
use LazarusPhp\LazarusDb\TableManagement\TableControl;
use LazarusPhp\LazarusDb\SchemaBuilder\SchemaLoader;
use LazarusPhp\LazarusDb\TableManagement\CoreFiles\TableCore;

enum SchemaDropActions :string
{
    case table = "table";
    case fk = "fk";
    case index = "index";
    case unique = "unique";
    case column = $column;
}

class Schema extends SchemaCore
{
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

    public static  function getTable()
    {
        return self::$table;
    }


    public function create(callable $table)
    {
        SchemaActions::method(__FUNCTION__);
        self::$sql = "CREATE TABLE IF NOT EXISTS " . self::$table . " (";
        if (is_callable($table)) {
            $class = new SchemaActions();
            $table($class);
            self::$sql .= $class->build();
        }
        self::$sql .= ")";
        !$this->save() ? SchemaErrors::generate(
            "Schema Build Failed",
            ["table" => self::$table, "reason" => self::$sql]
        ) : "";
        // Count Migrations Errors

        // Display Errors if any occur
        if (SchemaErrors::countErrors()) {
            SchemaErrors::loadErrors();
        }
    }

    public function alter(callable $table)
    {
        SchemaActions::method(__FUNCTION__);
        self::$sql = "ALTER TABLE " . self::$table . " ";
        if (is_callable($table)) {
            $class = new SchemaActions();
            $table($class);
            self::$sql .= $class->build();
        }
        echo self::$sql;
        $this->save();
        // Count Migrations Errors

        if (SchemaErrors::countErrors()) {
            SchemaErrors::loadErrors();
        }
    }

    public function requiresFirst(...$args)
    {
        
        foreach ($args as $key => $value) {
            $validator = new SchemaValidator(self::$table);
            if (!$validator->hasTable()) {
                SchemaLoader::load(ROOT . "/Migrations/Schemas", "up", $value);
            }
            else
            {
                echo "table not loaded ";
            }
        }
        return $this;
    }


    public function rename($table2)
    {
        self::$sql = "RENAME TABLE " . self::$table . " TO $table2";
        $result = $this->save();

        return $result ? true : false;
    }


    /**
     * Drop
     *
     * @param array $name
     * Deletes the table completly if it exists;
     * @return void
     */
    public function drop(SchemaDropActions $action,$name)
    {
        if(is_string($action))
        {
            $action = SchemaDropActions::from($action);
        }   

        if($action = "table")
        {
            self::$sql = "DROP TABLE IF EXISTS " . self::$table;
        }
        elseif($action = "fk")
        {
            self::$sql = "ALTER TABLE " . self::$table . " DROP FOREIGN KEY $name";
        }
        elseif($action = "index")
        {
            self::$sql = "ALTER TABLE " . self::$table . " DROP INDEX $name";
        }
        elseif($action = "unique")
        {
            self::$sql = "ALTER TABLE " . self::$table . " DROP INDEX $name";
        }
        elseif($action = "column")
        {
            self::$sql = "ALTER TABLE " . self::$table . " DROP COLUMN $name";
        }   
        
        return $this->save() ? true : false;
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
        self::$sql = "TRUNCATE TABLE " . self::$table;
        $result = $this->save();
        return $result ? true : false;
    }
};
