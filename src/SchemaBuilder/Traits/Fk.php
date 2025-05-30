<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder\Traits;

use LazarusPhp\LazarusDb\SchemaBuilder\Schema;

trait Fk
{


    protected static $fk = [];
    protected static $refTable = [];
    protected static $refcol = [];
    protected static $action = [];



    public function processFk()
    {
        $table = Schema::getTable();
      if(!isset(self::$fk[$table]))
      {
        // Supports refcol reftable and column
        self::$fk[$table] = [];
        // Delete and update and constraint = true or false;
        self::$action[$table] = [];
      }

      return true;
    }


    public function constraint($refTable,$refColumn)
    {
        $table = Schema::getTable();
        self::$action[$table]["constraint"] = true;
        $this->fk($refTable,$refColumn); 
        return $this;
    }

        public function onUpdate($action)
    {
        $table = Schema::getTable();
        $action = $this->actions($action);
        self::$action[$table]["update"] = $action;
        return $this;
    }

    public function onDelete($action)
    {
        $table = Schema::getTable();
        $action = $this->actions($action);
        self::$action[$table]["delete"] = $action;
        return $this;
    }


    public function fk($refTable,$refColumn){
        $this->processFk();
        $table = Schema::getTable();
        self::$fk[$table]["column"] = $this->name;
        self::$fk[$table]["refTable"] = $refTable;
        self::$fk[$table]["refColumn"] = $refColumn;
        return $this;
    }

    private function actions(string $action)
    {
        $action = strtoupper($action);

        $supportedActions = [
            'CASCADE',
            'RESTRICT',
            'NULL',
            'NOACTION',
            'DEFAULT'
        ];


        if(in_array($action,$supportedActions))
        {switch($action)
        {
            case 'CASCADE':
                return "CASCADE";
            case 'RESTRICT':
                return "RESTRICT";
            case 'NULL':
                return "SET NULL";
            case 'NOACTION':
                return "NO ACTION";
            case 'DEFAULT':
                return "SET DEFAULT";
        }
    }
        else
        {
            echo "Unsupported Action $action";
        }
         
    }


public function loadFk()
{
    $table = Schema::getTable();
    if (isset(self::$fk[$table]) && !empty(self::$fk[$table])) {
        $fkSql = [];
        $fk = self::$fk[$table];
        if (isset($fk['column'], $fk['refTable'], $fk['refColumn'])) {
            $constraint = (isset(self::$action[$table]["constraint"]) && self::$action[$table]["constraint"] === true)
                ? "CONSTRAINT fk_{$table}_{$fk['column']} "
                : "";
            $update = isset(self::$action[$table]["update"]) ? self::$action[$table]["update"] : "RESTRICT";
            $delete = isset(self::$action[$table]["delete"]) ? self::$action[$table]["delete"] : "RESTRICT";
            $fkSql[] = "{$constraint} FOREIGN KEY (`{$fk['column']}`) REFERENCES `{$fk['refTable']}`(`{$fk['refColumn']}`) ON DELETE $delete ON UPDATE $update";
        }
        $data = implode(", ", $fkSql);
        $this->query["fk"] = $data;
    }
}

}