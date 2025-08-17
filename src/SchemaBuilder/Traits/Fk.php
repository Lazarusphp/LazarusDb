<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder\Traits;

use App\System\Core\Functions;
use LazarusPhp\LazarusDb\SchemaBuilder\Schema;
use LazarusPhp\LazarusDb\SchemaBuilder\SchemaValidator;

trait Fk
{

    private static array  $fk = [];
    private static $fkName;
    private static $constraint = [];

    private static function hasfk($column)
    {
        $validator = new  SchemaValidator(self::$table);
        return $validator->hasForeignKey($column);
    }
    
    // Column will be the constraint name.
    public function foreignKey(string $column,$constraint=false)
    {
        if(!in_array($column,self::$constraint))
        {
            self::$constraint[] = $column;
        }
        self::$fkName = $column;
        $constraint = ($constraint===true) ? true : false;
        $actions = ["table"=>self::$table,"column"=>$column,"constraint"=>$constraint];
         $this->processRequest(self::$fkName,"fk",$actions);

        return $this;
    }

     public function rebase($column="")
    {
        if(!empty($column))
        {
            self::$fkName = $column;
        }
        
        $actions = ["name"=>self::$fkName,"action"=>"rebase","command"=>"DROP FOREIGN KEY "];
        $this->processRequest(self::$fkName,"fkRebase",$actions);
        // unset(self::$fkName);
    }
    public function reference($refTable,$refColumn)
    {
        $actions = ["referenceTable"=>$refTable,"referenceColumn"=>$refColumn];
         $this->processRequest(self::$fkName,"fkReference",$actions);
        return $this;
    }

   
    public function onUpdate($action = "cascade")
    {
        $actions = ["command"=>"$action"];
        $this->processRequest(self::$fkName,"fkUpdate",$actions);
        return $this;
    }

    public function onDelete($action = "cascade")
    {
        $actions = ["command"=>"$action"];
        $this->processRequest(self::$fkName,"fkDelete",$actions);
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

}