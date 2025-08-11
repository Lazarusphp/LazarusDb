<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder\Traits;

use LazarusPhp\LazarusDb\SchemaBuilder\Schema;
use LazarusPhp\LazarusDb\SchemaBuilder\SchemaActions;
use App\System\Core\Functions;
trait Defaults
{

    private function processDefaults()
    {
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
                $this->default(Functions::now());
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
