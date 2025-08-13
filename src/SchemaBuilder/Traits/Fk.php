<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder\Traits;

use App\System\Core\Functions;
use LazarusPhp\LazarusDb\SchemaBuilder\Schema;
use LazarusPhp\LazarusDb\SchemaBuilder\SchemaValidator;

trait Fk
{

    private static array  $fk = [];

    // tablename ColumnName if constraint is true command 1 and 2 are on update or ondelete

    public function fk($table="",$column="",$constraint=false)
    {
        $actions = ["currentColumn"=>$this->name,"table"=>$table,"column"=>$column,"cont"=>$constraint];
        $this->processRequest($this->name,"fk",$actions);
        return $this;
    }
    public function onUpdate($action = "cascade")
    {
        $actions = ["command"=>"$action"];
        $this->processRequest($this->name,"fkUpdate",$actions);
        return $this;
    }

    public function onDelete($action = "cascade")
    {
     
        $actions = ["command"=>"$action"];
        $this->processRequest($this->name,"fkDelete",$actions);
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