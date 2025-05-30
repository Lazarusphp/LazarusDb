<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder;

use LazarusPhp\LazarusDb\SchemaBuilder\CoreFiles\SchemaCore;
use LazarusPhp\LazarusDb\SchemaBuilder\Interfaces\TableInterface;
use LazarusPhp\LazarusDb\SchemaBuilder\Traits\Datatypes;
use LazarusPhp\LazarusDb\SchemaBuilder\Traits\Indexes;
use LazarusPhp\LazarusDb\SchemaBuilder\Traits\Nullable;
use LazarusPhp\LazarusDb\SchemaBuilder\Traits\Defaults;
use LazarusPhp\LazarusDb\SchemaBuilder\Traits\Attributes;
use LazarusPhp\LazarusDb\SharedAssets\Traits\TableControl;
use LazarusPhp\LazarusDb\SchemaBuilder\Traits\Fk;

// use LazarusPhp\LazarusDb\SharedAssets\Traits\ArrayControl;

class Table extends SchemaCore implements TableInterface
{
    use TableControl;
    use Datatypes;
    use Indexes;
    use Fk;
    use Defaults;
    use Nullable;
    use Attributes;
    // use ArrayControl;

    public $buildFailed = false;

    public function modify()
    {
        $this->method[$this->name] = " MODIFY COLUMN ";
    }

    public function drop()
    {
        $this->datatype[$this->name][] = "drop";
        $this->method[$this->name] = " DROP COLUMN ";
    }

    public function rename($original, $new)
    {
        $this->datatype[$this->name][] = "rename";
        $this->query[$this->name][] = " RENAME $original TO $new ";
    }

    private function modifier($name)
    {
        return array_key_exists($name, $this->method) ? $this->method[$name] : " ";
    }



 

    public function fragmentBuilder()
    {
        
        //$this->extras ie primary key, index, unique, foreign key
        $columns=[];

        // Do checks if the values are empty or if the keys in array, isset etc
        foreach($this->datatype as $key => $value)
        {


            if(isset(self::$migrationFailed[$key]) && self::$migrationFailed[$key] === true)
            {
                echo "Failed to save table $key";
                dd(self::$migrationError[$key]);
            }
            else
            {
                echo "We will save table : $key ";
                
               foreach($value as $section => $value)
                {
                    $null = isset($this->null[$key][$section]) ? $this->null[$key][$section] : " NOT NULL ";
                    $ai = isset($this->ai[$key][$section]) ? $this->ai[$key][$section] : " ";
                    $attributes = isset($this->attributes[$key][$section]) ? $this->attributes[$key][$section] : " ";
                    $defaults = isset($this->defaults[$key][$section]) ? $this->defaults[$key][$section] : " ";
                    $columns[] = "$value $attributes $null $defaults $ai";
                }
            }
        // unset($this->datatype[$key]);
        }

        $this->query["datatypes"] = $columns;
        return;
        }

    public function build()
    {
        // Return as a string.
            $this->fragmentBuilder();
            $this->loadPrimaryKey();
            $this->processIndexes();
            $this->loadFk();
            $columns = [];
            foreach($this->query as $key => $value)
            {
                // Check if Load Primary key and indexes are in an array
                if (is_array($value)) {
                    foreach ($value as $item) {
                        $columns[] = $item;
                    }
                    // output data as normal;
                } else {
                    $columns[] = $value;
                }
            }
            dd(Schema::$migrationError);
            unset(self::$fk[self::$table]);
            unset(self::$action[self::$table]);
            unset($this->attributes[self::$table]);
            unset($this->datatype[self::$table]);
            unset($this->method[self::$table]);
            unset($this->null[self::$table]);
            unset($this->ai[self::$table]);
            unset($this->defaults[self::$table]);
            unset($this->query[self::$table]);
            // implode the array to text with a trailing comma
            return implode(",", $columns);
    }
}
