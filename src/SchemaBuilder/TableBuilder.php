<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder;

class TableBuilder
{
    private $query = [];
    public $method = [];
    private $name;
    private $sql;
    
    public function int($name)
    {
        
        $this->name = $name;
        $this->query[$name] = " $name  INT ";
        return $this;
    }

        public function string($name)
    {
        $this->name = $name;
        $this->query[$name] = " $name  VARCHAR(255) ";
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

    public function rename($t1,$t2)
    {
        $this->query[$this->name] .= " RANAME $t1 to $t2 ";
    }

    public function ai()
    {
        $this->query[$this->name] .= " AUTO INCREMENT ";
        return $this;
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
        echo implode(",",$column);
    }


}