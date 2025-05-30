<?php
namespace LazarusPhp\LazarusDb\SchemaBuilder\Traits;
use LazarusPhp\LazarusDb\SchemaBuilder\Schema;

trait Nullable
{

    protected $null = [];

    private function processNull()
    {
        $table = Schema::getTable();
        if(!isset($this->null[$table]))
        {
            $this->null[$table] = [];
        }

        if(!array_key_exists($this->name,$this->null[$table]))
        {
             $this->null[$table][$this->name] = " NULL ";
        }
        else
        {
            Schema::$migrationError[$table] = "you cannot set nullable to $this->name more than once";
            Schema::$migrationFailed[$table] = true;
            return false;
        }
    }

       public function nullable()
    {
        $this->processNull();    
        return $this;
    }
}