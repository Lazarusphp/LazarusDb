<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder\Traits;

use LazarusPhp\LazarusDb\SchemaBuilder\Schema;
use LazarusPhp\LazarusDb\SchemaBuilder\SchemaActions;

trait Defaults
{

    private function processDefaults()
    {
        // Define new Array#
        // $table = Schema::getTable();
        // // Set a new datatype if it doesnt exist;
        // if (!isset($this->defaults[$table])) {
        //     $this->defaults[$table] = [];
        // }

        // if (array_key_exists($this->name, $this->defaults[$table])) {
        //     Schema::$migrationError[$table] = "Cannot Add Duplicate ";
        //     Schema::$migrationFailed[$table] = true;
        //     return false;
        // }
        return true;
    }

    // Defaults
    public function now($astimestamp = true)
    {
        if ($this->processDefaults()) {
            if ($astimestamp === false) {
                $value = "CURRENT_TIMESTAMP";
                   $this->processRequest($this->name,"default", [
                "command" => " DEFAULT $value "
            ]);
            } else {
                $this->default(NOW());
            }
         
        }

        return $this;
    }

    public function default(string|int $value)
    {
        if (empty($value)) {
            echo "Value cannot be empty";
        } else {
            if ($this->processDefaults()) {
                if (is_string($value)) {
                    $value = "'" . str_replace("'", "''", $value) . "'";
                }

                $this->processRequest($this->name,"default",[
                    "command" => "DEFAULT $value"
                ]);
            }
        }

        return $this;
    }
}
