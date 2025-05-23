<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder;

use LazarusPhp\LazarusDb\Database\CoreFiles\Database;
use LazarusPhp\LazarusDb\SchemaBuilder\CoreFiles\SchemaCore;
use LazarusPhp\LazarusDb\SchemaBuilder\Interfaces\TableInterface;
use LazarusPhp\LazarusDb\SchemaBuilder\Traits\Indexes;
use LazarusPhp\LazarusDb\SharedAssets\Traits\TableControl;
use LazarusPhp\LazarusDb\SchemaBuilder\Traits\Fk;

class Table extends SchemaCore implements TableInterface 
{
    use TableControl;
    use Indexes;
    use Fk;

        public function tinyInt(string $name)
    {
        
        $this->name = $name;
        $this->query[$name] = " $name  TINY INT(1) ";
        return $this;
    }

    public function int(string $name)
    {
        $this->name = $name;
        $this->query[$this->name] = " $name  INT ";
        return $this;
    }

        public function bigInt(string $name)
    {
        
        $this->name = $name;
        $this->query[$name] = " $name  BIG INT ";
        return $this;
    }

        public function varchar(string $name, int $value=100)
    {
        $this->name = $name;
        $this->query[$name] = " $name  VARCHAR($value) ";
        return $this;
    }

    public function text(string $name)
    {
        $this->name = $name;
        $this->query[$this->name] = " $name TEXT ";
        return $this;
    }

       public function mediumText(string $name)
        {
        $this->name = $name;
        $this->query[$this->name] = "$name MEDIUMTEXT ";
        return $this;
        }

        public function longText(string $name)
        {
        $this->name = $name;
        $this->query[$this->name] = "$name LONGTEXT ";
        return $this;
    }

       public function date(string $name)
    {
        $this->name = $name;
        $this->query[$this->name] = "$name DATE ";
        return $this;
    }

    public function dateTime(string $name)
    {
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