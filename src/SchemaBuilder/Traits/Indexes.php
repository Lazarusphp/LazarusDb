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
    protected $ai = [];
    protected $requirePrimary = false;

    private static $countIndex = [];
 

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

    public function index($key = "idx_default",...$args)
    {
        self::$index = $key;
        // do a check if in modify mode add index here
         // Validate that the name and the add command cannot be used together
        if(count($args) === 0){
        $value="";
        }
        else{
        $value = count($args) === 1 ? (string) $args[0] : $args;
        }
        $command = "INDEX";
        $actions = [
            $key => [
                "name" => self::$index,
                "value"=>$value,
                "command"=>" $command ",
                "reference" => self::$index
            ]
        ];
        $this->processRequest($this->name, "indexes", $actions);
    }


    public function unique($key = "idx_default",...$args)
    {
        self::$index = $key;
        // do a check if in modify mode add index here
         // Validate that the name and the add command cannot be used together
        if(count($args) === 0){
        $value="";
        }
        else{
        $value = count($args) === 1 ? (string) $args[0] : $args;
        }

        $actions = [
            $key => [
                "name" => self::$index,
                "value"=>$value,
                "command"=>" UNIQUE ",
                "reference" => self::$index,
            ],
        ];

        $this->processRequest($this->name, "uniques", $actions);
    }
}
