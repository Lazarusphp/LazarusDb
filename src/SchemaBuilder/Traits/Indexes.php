<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder\Traits;

use LazarusPhp\LazarusDb\SchemaBuilder\Schema;
use LazarusPhp\LazarusDb\SchemaBuilder\SchemaActions;
use LazarusPhp\LazarusDb\SharedAssets\Traits\ArrayControl;

trait Indexes
{
    protected static $primaryKey = [];
    protected static $index = [];
    protected static $indexKey = [];
    protected static $indexType = []; // index or unique
    protected $ai = [];
    protected $requirePrimary = false;

    private static $countArray = [];
    private function processAi()
    {
        $this->processRequest($this->name, "ai", [
            "command" => " AUTO_INCREMENT "
        ]);
        $this->primary();
    }

    public function processIndexes()
    {
        $table = $this->getTable();

        if (isset(self::$index[$table])) {
            if (count(self::$index[$table]) > 1) {
                foreach (self::$indexType[$table] as $value => $type) {
                    if ($type === "index") {
                        $this->loadIndexes();
                    }

                    if ($type === "unique") {
                        $this->loadUniques();
                    }
                }
            }
        }
    }




    public function ai()
    {
        $this->processAi();
        return $this;
    }

    public function loadPrimaryKey()
    {
        $table = $this->getTable();
        if ($this->requirePrimary === false) {
            // Check if the pk has been set in primary key and if not default to id;
            $id = isset(self::$primaryKey[$table]["pk"]) ? self::$primaryKey[$table]["pk"] : "id";

            if (isset(self::$primaryKey[$table]["pk"])) {
                self::$query["pk"] = " PRIMARY KEY ($id) ";
            }
        } else {
            Schema::$migrationError[$table] = "Primary key is required when adding auto increment";
            Schema::$migrationFailed[$table] = true;
        }
    }

    public function setPrimary()
    {

        if (!isset(self::$countArray["primary"]) || !is_array(self::$countArray["primary"])) {
            self::$countArray["primary"] = [];
        }
        self::$countArray["primary"][] = $this->name;

        if (count(self::$countArray["primary"]) > 1) {
            Schema::$migrationError[self::$table] = "Primary key can only be set once per table";
            Schema::$migrationFailed[self::$table] = true;
            return $this;
        } else {
            $actions = [
                "command" => " PRIMARY KEY ({$this->name}) "
            ];

            $this->processRequest($this->name, "primary", $actions);
            return $this;
        }
    }


    public function setUnique($reference)
    {
        $this->processRequest($this->name, "unique", [
            "type" => "unique",
            "name" => $this->name,
            "references" => $reference,
        ]);
        return $this;
    }

    // Changed name from primaryKey to primary
    public function primary()
    {
        $actions = ["command" => " PRIMARY KEY (id) "];
        $this->processRequest($this->name, "primary", $actions);
        return $this;
    }

    public function index($key = "idx_default")
    {
        $actions = [
            $key => [
                "name" => $this->name,
                "reference" => $key
            ]
        ];

        $this->processRequest($this->name, "indexes", $actions);

        // $table = $this->getTable();
        // if(!isset(self::$index[$table]))
        // {
        //     self::$index[$table] = [];
        //     self::$indexKey[$table] = [];
        //     self::$indexType[$table] = [];
        // }
        //     self::$index[$table][$this->name] = $this->name;
        //     self::$indexKey[$table][$this->name] = $key;
        //     self::$indexType[$table][$this->name] = "index";
        return $this;
    }


    private function loadUniques()
    {
        $table = $this->getTable();;
        foreach (self::$index[$table] as $key => $unique) {
            $unique_name = isset(self::$indexKey[$key]) ? "unique_" . self::$indexKey[$key] : "unique_default";
            $columns[] = $unique;


            self::$query["uniques"] =  "CONSTRAINT UNIQUE $unique_name (" . implode(',', $columns) . ") ";
        }
    }

    public function unique($key = "unique_")
    {
        
        $actions = [
            $key => [
                "name" => $this->name,
                "reference" => $key
            ]
        ];

        $this->processRequest($this->name, "uniques", $actions);
        return $this;
    }
}
