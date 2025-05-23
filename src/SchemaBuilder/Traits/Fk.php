<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder\Traits;

trait Fk
{

    protected $fk = [];
    protected $fkCol;
    protected $refTable = [];
    protected $refCol = [];

    protected $ondelete = false;
    protected $delete = [];
    protected $onupdate = false;
    protected $update = [];

    protected $constraint = false;

    public function constraint(string $column)
    {
        $this->constraint = true;
        $this->fkCol = $column;
        return $this;
    }

    public function foreignKey(string $column = "")
    {
        if(!isset($this->constraint) && $this->constraint === false && !empty($column))
        {
             $this->fkCol = $column;
        }
        $this->fk[$this->fkCol] = $column;
        return $this;
    }

    public function references($table,$column)
    {
        $this->refTable[$this->fkCol] = $table;
        $this->refCol[$this->fkCol] = $column;
        return $this;
    }

    public function loadFk()
    {
        foreach($this->fk as $key => $fk)
        {
            
            $constraints = $this->constraint === true ? " CONSTRAINT fk_".$fk." ": " ";
            $delete = array_ex? " ON DELETE $option"         $this->query["fk"] = $constraints . "FOREIGN KEY fk_".$fk." (".$fk.") REFERENCES " . $this->refTable[$key] . "(" . $this->refCol[$key] . ") ";
            
        }

        return implode(",", $this->query);
    }


}