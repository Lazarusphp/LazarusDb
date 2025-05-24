<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder\Traits;

trait Fk
{

    protected $fk = [];
    protected $fkCol;
    protected $refTable = [];
    protected $refCol = [];

    protected $constraint = false;
    protected $delete = [];
    protected $update = [];

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

        $this->keyExists($this->fkCol, $this->fk, "Foreign key column $this->fkCol already exists");

        $this->fk[$this->fkCol] = $column;
        return $this;
    }

    public function references($table,$column)
    {
        if(!$this->keyExists($this->fkCol, $this->refTable, "Reference table for foreign key $this->fkCol already exists"))
        {
             $this->refTable[$this->fkCol] = $table;
             $this->refCol[$this->fkCol] = $column;
            return $this;
        }
        echo "Reference table for foreign key $this->fkCol already exists";
        return false;

    }

    protected function actions(string $action)
    {
        $action = strtoupper($action);

        $supportedActions = [
            'CASCADE',
            'RESTRICT',
            'NULL',
            'NOACTION',
            'DEFAULT'
        ];


        $this->inArray($action,$supportedActions,"Action $action is not supported");

        switch($action)
        {
            case 'CASCADE':
                return "CASCADE";
            case 'RESTRICT':
                return "RESTRICT";
            case 'NULL':
                return "SET NULL";
            case 'NOACTION':
                return "NO ACTION";
            case 'DEFAULT':
                return "SET DEFAULT";
        }   
    }
    public function onDelete($action="RESTRICT")
    {
        $action = $this->actions($action);
        $this->keyExists($this->fkCol, $this->delete, "Foreign key column $this->fkCol already exists");
        $this->delete[$this->fkCol] = $action;
        return $this;
    }

    public function onUpdate($action="RESTRICT")
    {
        $action = $this->actions($action);
        $this->keyExists($this->fkCol, $this->update, "Foreign key column $this->fkCol already exists");
        $this->update[$this->fkCol] = $action;
        // echo $this->update[$this->fkCol];
        return $this;
    }

    public function loadFk()
    {
        if(count($this->fk) >= 1)
        {
            foreach($this->fk as $key => $fk)
            {
                $constraints = $this->constraint === true ? " CONSTRAINT fk_".$fk." ": " ";
                $delete = isset($this->delete[$key]) ? $this->delete[$key] : " RESTRICT ";
                // echo $delete;
                $update = isset($this->update[$key]) ? $this->update[$key] : " RESTRICT ";
        
                $reference = isset($this->refTable[$key]) ? $this->refTable[$key] : exit("Reference table for foreign key $fk does not exist");
                // echo $update;
                $this->query[] = "FOREIGN KEY ".$constraints."(".$fk.") REFERENCES  $reference (".$this->refCol[$key].") ON DELETE $delete  ON UPDATE $update";
            }

        return implode(",", $this->query);
        }
    }


}