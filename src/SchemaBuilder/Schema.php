<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder;

use LazarusPhp\LazarusBridge\SchemaValidator;
use LazarusPhp\LazarusDb\SchemaBuilder\CoreFiles\SchemaCore;
use LazarusPhp\LazarusDb\SchemaBuilder\SchemaActions;
use LazarusPhp\LazarusDb\SchemaBuilder\SchemaLoader;


enum SchemaDropActions :string
{
    case table = "table";
    case fk = "fk";
    case index = "index";
    case unique = "unique";
    case column = "column";
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
        // echo self::$sql;
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
            if ($validator === true) {
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
    public function drop(SchemaDropActions|string $action, string $name)
    {
        if (is_string($action)) {
            $action = SchemaDropActions::from($action);
        }
        if ($action === SchemaDropActions::table) 
        {
            
            $validator = new SchemaValidator($name);
            if($validator === false)
            {
                echo "No Table Found";
                SchemaErrors::generate("Cannot Drop Table",["reason"=>"Table $name Does not exist"]);
                return false;
            }
            
            // If true Drop the table.
            self::$sql = "DROP TABLE IF EXISTS  $name ";
        } elseif ($action === SchemaDropActions::fk) {
        
            self::$sql = "ALTER TABLE " . self::$table . " DROP FOREIGN KEY $name";
        } elseif ($action === SchemaDropActions::index) {
            $validator = new SchemaValidator(self::$table);

            if(is_array($name))
            {
                SchemaErrors::generate("Cannot Drop Index",["reason"=>"Index name must be a string, array given"]);
                return false;
            }
            if($validator->hasIndexes($name) === false)
            {
                SchemaErrors::generate("Cannot Drop Index",["reason"=>"Index $name Does not exist"]);
                return false;
            }
            else
            {
                self::$sql = "ALTER TABLE " . self::$table . " DROP INDEX $name";
            }
           
        } elseif ($action === SchemaDropActions::unique) {
            $validator = new SchemaValidator(self::$table);
            if($validator->hasUnique($name) === false)
            {
                SchemaErrors::generate("Failed to Drop Unique",["reason"=>"Unique name $name cannot be found"]);
                return false;
            }
            self::$sql = "ALTER TABLE " . self::$table . " DROP INDEX $name";
        } elseif ($action === SchemaDropActions::column) {
            
        $validator = new SchemaValidator(self::$table);

        if($validator === false)
        {
            SchemaErrors::generate("Cannot Drop Column",["reason"=>"Table ".self::$table." Does not exist"]);
            return false;
        }

        if (is_array($name)) {
            SchemaErrors::generate("Cannot Drop Column",["reason"=>"Column name must be a string, array given"]);
            return false;
        }

        if($validator->hasColumn($name) === false)
        {
            SchemaErrors::generate("Cannot Drop Column",["reason"=>"Column $name Does not exist"]);
            return false;
        }
        echo "Dropping Column $name";
            self::$sql = "ALTER TABLE " . self::$table . " DROP COLUMN $name";
        }
        // echo self::$sql;
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
