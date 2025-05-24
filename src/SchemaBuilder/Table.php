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

        public function tinyInt(string $name)
    {
        
          $this->keyExists($name,$this->query,"Column $name already exists");

         
        $this->name = $name;
        $this->query[$name] = " $name  TINY INT(1) ";
        return $this;
        
    }

    public function int(string $name)
    {

        $this->keyExists($name,$this->query,"Column $name already exists");

        $this->name = $name;
        $this->query[$this->name] = " $name  INT ";
        return $this;
        
    }

        public function bigInt(string $name)
    {
               $this->keyExists($name,$this->query,"Column $name already exists");

        $this->name = $name;
        $this->query[$name] = " $name  BIG INT ";
        return $this;
        
    }

        public function varchar(string $name, int $value=100)
    {
        $this->keyExists($name,$this->query,"Column $name already exists");
        $this->name = $name;
        $this->query[$name] = " $name  VARCHAR($value) ";
        return $this;

    }

    public function text(string $name)
    {
        $this->keyExists($name,$this->query,"Column $name already exists");

        $this->name = $name;
        $this->query[$this->name] = " $name TEXT ";
        return $this;

    }

       public function mediumText(string $name)
        {
        $this->keyExists($name,$this->query,"Column $name already exists");

            $this->name = $name;
            $this->query[$this->name] = "$name MEDIUMTEXT ";
            return $this;
        
        }

        public function longText(string $name)
        {
        $this->keyExists($name,$this->query,"Column $name already exists");
    
        $this->name = $name;
        $this->query[$this->name] = "$name LONGTEXT ";
        return $this;
        
    }

       public function date(string $name)
    {
        $this->keyExists($name,$this->query,"Column $name already exists");

        $this->name = $name;
        $this->query[$this->name] = "$name DATE ";
        return $this;

    }

    public function dateTime(string $name)
    {
        $this->keyExists($name,$this->query,"Column $name already exists");
        $this->name = $name;
        $this->query[$this->name] = "$name DATETIME ";
        return $this;

    }

    public function modify()
    {
        $this->method[$this->name] = " MODIFY COLUMN ";
    }

    public function drop()
    {
        $this->method[$this->name] = " DROP COLUMN ";
    }

    public function rename($original,$new)
    {
        $this->query[$this->name] .= " RANAME $original to $new ";
    }

    private function modifier($name)
    {
        return array_key_exists($name,$this->method) ? $this->method[$name] : " ";
    }


    // Defaults

    
    public function now()
    {
        $this->query[$this->name] .= " DEFAULT (CURRENT_DATE) ";
        return $this;
    }

    public function timestamp()
    {
        $this->query[$this->name] .= " DEFAULT (CURRENT_TIMESTAMP) ";
        return $this;
    }

    public function default(string|int $value)
    {
        if (is_string($value)) {
            // Escape single quotes for SQL and wrap in single quotes
            $escaped = str_replace("'", "''", $value);
            $this->query[$this->name] .= " DEFAULT '$escaped' ";
        } else {
            $this->query[$this->name] .= " DEFAULT $value ";
        }
        return $this;
    }




    
    public function buildSql()
    {
   
        $column = [];
        foreach($this->query as $key => $value)
        {
            $column[] = $this->modifier($key) . $value;
        }
        return implode(",",$column);

    }


}