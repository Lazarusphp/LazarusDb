<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder\Traits;

use LazarusPhp\LazarusDb\SchemaBuilder\Schema;
use LazarusPhp\LazarusDb\SchemaBuilder\SchemaActions;
use LazarusPhp\LazarusDb\SchemaBuilder\SchemaErrors;

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
    if (self::tableControl()->hasTable($table) === false) {
      if (!self::tableControl()->validFields("column_name", $name)) {
        SchemaErrors::generate("Cannot Adjust Position", ["reason" => "Table $table doesnt exist"]);
        return false;
      } elseif (self::tableControl()->hasTable($table)) {
        if (!self::tableControl()->validFields("column_name", $name)) {
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
if (self::tableControl()->hasTable($table) === false) {
      if (!self::tableControl()->validFields("column_name", $name)) {
        SchemaErrors::generate("Cannot Adjust Position", ["reason" => "Table $table doesnt exist"]);
        return false;
      } elseif (self::tableControl()->hasTable($table)) {
        if (!self::tableControl()->validFields("column_name", $name)) {
          SchemaErrors::generate("Cannot Adjust Position", ["reason" => "Table $table doesnt exist"]);
          return false;
        }
      }
    }
      else
     {
      $this->processPosition(" FIRST ");
    }
    return $this;
  }
}
