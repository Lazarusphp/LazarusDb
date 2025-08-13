<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder\Traits;

use LazarusPhp\LazarusDb\SchemaBuilder\Schema;
use LazarusPhp\LazarusDb\SchemaBuilder\SchemaActions;
use LazarusPhp\LazarusDb\SchemaBuilder\SchemaErrors;
use LazarusPhp\LazarusDb\SchemaBuilder\SchemaValidator;

trait Position
{

  private function processPosition($command)
  {
    SchemaActions::params($this->name, "position", [
      "command" => $command
    ]);
  }

  public function after($name)
  {
    $table = self::$table;
    $validator = new SchemaValidator($table);
    if ($validator->hasTable() === false) {
      if (!$validator->validField("column_name", $name)) {
        SchemaErrors::generate("Cannot Adjust Position", ["reason" => "Table $table doesnt exist"]);
        return false;
      } elseif ($validator->hasTable($table)) {
        if (!$validator->validField("column_name", $name)) {
          SchemaErrors::generate("Cannot Adjust Position", ["reason" => "Table $table doesnt exist"]);
          return false;
        }
      }
    } else {
      $this->processPosition("AFTER $name");
      return $this;
    }
  }


  public function first()
  {
    $table = self::$table;
    $validator = new SchemaValidator($table);
    if ($validator->hasTable($table) === false) {
      if (!$validator->validField("column_name", $name)) {
        SchemaErrors::generate("Cannot Adjust Position", ["reason" => "Table $table doesnt exist"]);
        return false;
      } elseif ($validator->hasTable($table)) {
        if (!$validator->validField("column_name", $name)) {
          SchemaErrors::generate("Cannot Adjust Position", ["reason" => "Table $table doesnt exist"]);
          return false;
        }
      }
    } else {
      $this->processPosition(" FIRST ");
    }
    return $this;
  }
}
