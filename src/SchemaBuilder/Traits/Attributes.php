<?php
namespace LazarusPhp\LazarusDb\SchemaBuilder\Traits;

use LazarusPhp\LazarusDb\SchemaBuilder\Schema;
use LazarusPhp\LazarusDb\SchemaBuilder\SchemaActions;

trait Attributes
{
    public function unsigned()
    {
            $this->processRequest($this->name,"attributes",[
            "requiredDatatype" => "int|bigint|tinyint",
            "command"=>" UNSIGNED "
            ]);
        return $this;
    }

        public function binary()
    {
        $this->processRequest($this->name,"attributes",[
            "requiredDatatype" => "char|varchar|text|mediumtext|longtext",
            "command"=>" BINARY "
        ]);
        return $this;
    }

    public function currentTimestamp()
    {
        $this->processRequest($this->name,"attributes",[
            "requiredDatatype" => "timestamp|datetime",
            "command"=>" on update CURRENT_TIMESTAMP "
        ]);
        return $this;
    }
}