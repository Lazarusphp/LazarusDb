<?php
namespace LazarusPhp\LazarusDb\SchemaBuilder\Traits;
use LazarusPhp\LazarusDb\SchemaBuilder\Schema;
use LazarusPhp\LazarusDb\SchemaBuilder\SchemaActions;

trait Position
{
      
    private function processPosition($command)
    {
          SchemaActions::params($this->name,"position",[
          "command"=>$command
        ]);
    }

    public function after($name)
    {
      $table = self::$table;
      if(SchemaActions::tableControl()->hasTablebyColumn($name) === false)
      {
        Schema::$migrationError[$table] = "Column {$name} does not exist in table {$table}";
        Schema::$migrationFailed[$table] = true;
        return false;
      }
      else
      {
       $this->processPosition("AFTER $name");
      return $this;
      }
    }


    public function first()
    {
      $table = self::$table;
   
      if(SchemaActions::tableControl()->hasTablebyColumn($this->name) === false)
      {
        Schema::$migrationError[$table] = "Column {$this->name} does not exist in table {$table}";
        Schema::$migrationFailed[$table] = true;
        return false;
      }
      else
      {
             $this->processPosition(" FIRST ");
      }
      return $this;
    }
}
