<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder\Traits;

use LazarusPhp\LazarusDb\SchemaBuilder\Schema;

trait Defaults
{
    protected  $defaults = [];

    private function processDefaults()
    {
        // Define new Array#
        $table = Schema::getTable();
        // Set a new datatype if it doesnt exist;
        if (!isset($this->defaults[$table])) {
            $this->defaults[$table] = [];
        }

        if (array_key_exists($this->name, $this->defaults[$table])) {
            Schema::$migrationError[$table] = "Cannot Add Duplicate ";
            Schema::$migrationFailed[$table] = true;
            return false;
        }
        return true;
    }

    // Defaults
    public function now($astimestamp = true)
    {
        $table = Schema::getTable();
        if ($this->processDefaults()) {
            if ($astimestamp === false) {
                $this->defaults[$table][$this->name] = " DEFAULT (CURRENT_TIMESTAMP) ";
            } else {
                $this->defaults[$table][$this->name] = " DEFAULT (CURRENT_DATE) ";
            }
            return $this;
        }
    }


    public function default(string|int $value)
    {
        $table = Schema::getTable();
        if ($this->processDefaults()) {
            if (is_string($value)) {
                // Escape single quotes for SQL and wrap in single quotes
                $escaped = str_replace("'", "''", $value);
                $this->defaults[$table][$this->name] = " DEFAULT '$escaped' ";
            } else {
                $this->defaults[$table][$this->name][] = " DEFAULT $value ";
            }
            return $this;
        }
    }
}
