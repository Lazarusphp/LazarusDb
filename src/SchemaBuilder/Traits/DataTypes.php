<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder\Traits;

trait DataTypes
{

    /**
     * Integer 
     * @method int($name);
     */public function tinyInt(string $name)
    {
        // 
        $this->query .= "$name TINYINT(1) ";
        return $this;
    }

      public function int(string $name)
    {
        $this->query .= "$name INT";
        // Bind $param values to param
        return $this;
    }

    public function bigInt(string $name)
    {
         $this->query .= "$name BIGINT ";
        return $this;
    }

    public function varchar(string $name,int $value=255)
    {
       $this->query .= "$name VARCHAR($value) ";
       return $this;
    }

    public function text($name,)
    {
        $this->query .= "$name TEXT ";
        return $this;
        }

        public function mediumText(string $name)
        {
        $this->query .= "$name MEDIUMTEXT ";
        return $this;
        }

        public function longText(string $name)
        {
        $this->query .= "$name LONGTEXT ";
        return $this;
    }

    public function date($name)
    {
        $this->query .= "$name DATE ";
        return $this;
    }

    public function dateTime($name)
    {
        $this->query .= "$name DATETIME ";
        return $this;
    }
}