<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder\Traits;

use LazarusPhp\LazarusDb\SchemaBuilder\Schema;
use LazarusPhp\LazarusDb\SchemaBuilder\SchemaActions;
use LazarusPhp\LazarusDb\SchemaBuilder\SchemaErrors;
use LazarusPhp\LazarusDb\SchemaBuilder\SchemaValidator;
use LazarusPhp\LazarusDb\SharedAssets\Traits\ArrayControl;

trait Indexes
{
    protected static $primaryKey = [];
    protected static $index = [];
    protected static $indexKey = [];
    protected static $indexType = []; // index or unique
    protected $ai = [];
    protected $requirePrimary = false;

    private static $countIndex = [];
    private function processAi()
    {
  
    }

    public function ai()
    {
        $this->processRequest($this->name, "ai", [
            "command" => " AUTO_INCREMENT "
        ]);
        // Automatically Assign Primary
        $this->primary();
        return $this;
    }

    // Changed name from primaryKey to primary
    public function primary()
    {
        $id = ($this->name === "id") ? "id" : $this->name;
        $actions = ["command" => " PRIMARY KEY ($this->name) "];
        $this->processRequest($this->name, "primary", $actions);
        return $this;
    }

    public function index($key = "idx_default")
    {
     

        // do a check if in modify mode add index here
         // Validate that the name and the add command cannot be used together
        $command = "INDEX";
        $actions = [
            $key => [
                "name" => $this->name,
                "command"=>" $command ",
                "reference" => $key
            ]
        ];

        $this->processRequest($this->name, "indexes", $actions);
        return $this;
    }

    public function dropIndex($key)
    {
        // Validate that the name and the add command cannot be used together
         $actions = [
            $key => [
                "name" => $this->name,
                "command"=> " DROP INDEX $this->name",
                "reference" => $key
            ]
        ];
        $this->processRequest($this->name, "indexes", $actions);
        return $this;

    }

    public function unique($key = "unique_")
    {
        
        $actions = [
            $key => [
                "name" => $this->name,
                "command"=>" UNIQUE ",
                "reference" => $key
            ]
        ];

        $this->processRequest($this->name, "uniques", $actions);
        return $this;
    }
}
