<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder;

use LazarusPhp\LazarusDb\Database\CoreFiles\Database;
use LazarusPhp\LazarusDb\SchemaBuilder\CoreFiles\SchemaCore;
use LazarusPhp\LazarusDb\SchemaBuilder\Interfaces\TableInterface;
use LazarusPhp\LazarusDb\SchemaBuilder\Traits\Indexes;
use LazarusPhp\LazarusDb\SharedAssets\Traits\TableControl;
use LazarusPhp\LazarusDb\SchemaBuilder\Traits\Fk;
use LazarusPhp\LazarusDb\SharedAssets\Traits\ArrayControl;

class Table extends SchemaCore implements TableInterface
{
    use TableControl;
    use Indexes;
    use Fk;
    use ArrayControl;

    public $buildFailed = false;
    protected static $flags = [];
    protected $datatype = [];

    public function tinyInt(string $name)
    {
         $this->name = $name;
        $this->datatype[$this->name] = "tinyint";
        $this->keyExists($name, $this->query, "Column $name already exists");
       
        $this->query[$name] = " $name  TINY INT(1) ";
        return $this;
    }

    public function int(string $name)
    {
         $this->name = $name;
        $this->datatype[$this->name] = "int";
        if (self::keyExists($name, $this->query)) {
            $this->buildFailed = true;
            Schema::$migrationError[] = "Cannot use $name multiple times";
        } else {
            $this->query[$this->name] = " $name  INT ";
            return $this;
        }
    }

    public function bigInt(string $name)
    {

        if (self::keyExists($name, $this->query)) {
            $this->buildFailed = true;
            Schema::$migrationError[] = "Cannot use $name multiple times";
        } else {
            $this->name = $name;
            $this->datatype[$this->name] = "bigint";
            $this->query[$name] = " $name  BIG INT ";
            return $this;
        }
    }

    public function varchar(string $name, int $value = 100)
    {
        if (self::keyExists($name, $this->query)) {
            $this->buildFailed = true;
            Schema::$migrationError[] = "Cannot use $name multiple times";
        } else {
            $this->keyExists($name, $this->query, "Column $name already exists");
            $this->name = $name;
            $this->datatype[$this->name] = "varchar";
            $this->query[$name] = " $name  VARCHAR($value) ";
            return $this;
        }
    }

    public function text(string $name)
    {
        if (self::keyExists($name, $this->query)) {
            $this->buildFailed = true;
            Schema::$migrationError[] = "Cannot use $name multiple times";
        } else {
            $this->name = $name;
            $this->datatype[$this->name] = "text";
            $this->query[$this->name] = " $name TEXT ";
            return $this;
        }
    }

    public function mediumText(string $name)
    {
        if (self::keyExists($name, $this->query)) {
            $this->buildFailed = true;
            Schema::$migrationError[] = "Cannot use $name multiple times";
        } else {
            $this->name = $name;
            $this->datatype[$this->name] = "mediumtext";
            $this->query[$this->name] = "$name MEDIUMTEXT ";
            return $this;
        }
    }

    public function longText(string $name)
    {
        if (self::keyExists($name, $this->query)) {
            $this->buildFailed = true;
            Schema::$migrationError[] = "Cannot use $name multiple times";
        } else {
            $this->name = $name;
            $this->datatype[$this->name] = "longtext";
            $this->query[$this->name] = "$name LONGTEXT ";
            return $this;
        }
    }

    public function date(string $name)
    {
        if (self::keyExists($name, $this->query)) {
            $this->buildFailed = true;
            Schema::$migrationError[] = "Cannot use $name multiple times";
        } else {
            $this->name = $name;
            $this->datatype[$this->name] = "date";
            $this->query[$this->name] = "$name DATE ";
            return $this;
        }
    }

    public function dateTime(string $name)
    {
        if (self::keyExists($name, $this->query)) {
            $this->buildFailed = true;
            Schema::$migrationError[] = "Cannot use $name multiple times";
        } else {
            $this->name = $name;
            $this->datatype[$this->name] = "datetime";
            $this->query[$this->name] = "$name DATETIME ";
            return $this;
        }
    }

    public function modify()
    {
        $this->method[$this->name] = " MODIFY COLUMN ";
    }



    public function drop()
    {
        $this->datatype[$this->name] = "drop";
        $this->method[$this->name] = " DROP COLUMN ";
    }

    public function rename($original, $new)
    {
        $this->datatype[$this->name] = "rename";
        $this->query[$this->name] .= " RANAME $original to $new ";
    }

    private function modifier($name)
    {
        return array_key_exists($name, $this->method) ? $this->method[$name] : " ";
    }


    // Defaults


    public function now($astimestamp = false)
    {
        $this->datatype[$this->name] = "now";
        if ($astimestamp) {
            $this->query[$this->name] .= " DEFAULT (CURRENT_TIMESTAMP) ";
        } else {
            $this->query[$this->name] .= " DEFAULT (CURRENT_DATE) ";
        }
        return $this;
    }


    public function default(string|int $value)
    {
        $this->datatype[$this->name] = "default";
        if (is_string($value)) {
            // Escape single quotes for SQL and wrap in single quotes
            $escaped = str_replace("'", "''", $value);
            $this->query[$this->name] .= " DEFAULT '$escaped' ";
        } else {
            $this->query[$this->name] .= " DEFAULT $value ";
        }
        return $this;
    }

    public function unsigned()
    {
        $this->datatype[$this->name] = "unsigned";
        // Check if the current column type supports UNSIGNED
        $columnDef = $this->query[$this->name] ?? '';
        // Only allow UNSIGNED for numeric types
          $this->query[$this->name] .= " UNSIGNED ";
        
        return $this;
    }

    public function nullable(bool $bool = true)
    {
        $this->datatype[$this->name] = "nullable";
        ($bool === false) ? $this->query[$this->name] .= " NOT NULL " : $this->query[$this->name] .= " NULL ";
        return $this;
    }

    public function build()
    {
        if (!$this->buildFailed) {
            $this->getPrimaryKey();
            $this->getIndexes();
            $this->getUniques();
            $this->loadFk();
            $column = [];
            foreach ($this->query as $key => $value) {
                $column[] = $this->modifier($key) . $value;
            }
            return implode(",", $column);
        } else {
            echo "Build Faild";
        }
    }
}
