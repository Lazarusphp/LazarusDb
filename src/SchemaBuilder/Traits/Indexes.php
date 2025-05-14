<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder\Traits;

trait Indexes
{

    public function ai()
    {
       $this->query .= " AUTO_INCREMENT ";
       return $this;
    }


    public function primaryKey($name)
    {
        $this->query .= " PRIMARY KEY ($name)";
        return $this;
    }

    public function index(string $name)
    {
        $this->query .= "INDEX ($name) ";
        return $this;
    }

    public function unique($name)
    {
        $this->query .= "UNIQUE ($name) ";
        return $this;
    }
}