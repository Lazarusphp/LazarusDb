<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder;

use LazarusPhp\LazarusDb\SchemaBuilder\CoreFiles\SchemaCore;
use LazarusPhp\LazarusDb\SchemaBuilder\SchemaActions;
use LazarusPhp\LazarusDb\TableManagement\Table;
use LazarusPhp\LazarusDb\TableManagement\TableControl;
use LazarusPhp\LazarusDb\SchemaBuilder\SchemaLoader;
use LazarusPhp\LazarusDb\TableManagement\CoreFiles\TableCore;

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
        echo self::$sql;
        $this->save();
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
    public function drop()
    {
        self::$sql = "DROP TABLE IF EXISTS " . self::$table;
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
