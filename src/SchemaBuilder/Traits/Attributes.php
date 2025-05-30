<?php
namespace LazarusPhp\LazarusDb\SchemaBuilder\Traits;

use LazarusPhp\LazarusDb\SchemaBuilder\Schema;

trait Attributes
{
    private $attributes = [];
    private function processAttribute($command)
    {
      $table = Schema::getTable();
      if(!isset($this->attributes[$table]))
      {
        $this->attributes[$table] = [];
      }

      if(!array_key_exists($this->name,$this->attributes[$table])){
        $this->attributes[$table][$this->name] = $command;
      }
      else
      {
            Schema::$migrationError[$table] = "multiple attributes cannot be applid to {$this->name}";
            Schema::$migrationFailed[$table] = true;
            return false;
      }

    }

    public function unsigned()
    {
      
        // Only allow UNSIGNED for numeric types
        $this->processAttribute(" UNSIGNED ");
        return $this;
    }

        public function binary()
    {
        $this->processAttribute(" BINARY ");
        return $this;
    }
}