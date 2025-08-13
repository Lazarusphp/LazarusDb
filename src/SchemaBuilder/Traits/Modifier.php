<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder\Traits;

use LazarusPhp\LazarusDb\TableManagement\TableControl;
use LazarusPhp\LazarusDb\SchemaBuilder\Schema;
use LazarusPhp\LazarusDb\SchemaBuilder\SchemaActions;
use LazarusPhp\LazarusDb\SchemaBuilder\SchemaValidator;
use LazarusPhp\LazarusDb\TableManagement\Table;

trait Modifier
{
   
    private $oldname = "";


    private function ValidateTable($column)
    {
        $validator = new SchemaValidator(self::$table);
        
        if($validator->hasTable() && $validator->hasColumn($column))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function modify()
    {
        if($this->ValidateTable($this->name))
 {       $this->processRequest($this->name,"modifier",[
            "modifier"=>true,
            "name"=>$this->name,
            "type"=>"modify",
            "command"=>" MODIFY "
        ]);
        }
        else
        {
            self::unsetParams($this->name);
        }
    }

    public function rename($name)
    {
        $this->oldname = $name;
        return $this;
    }

    public function change()
    {
        $name  = ($this->oldname || !empty($this->oldname)) ? $this->oldname : $this->name;
        $this->processRequest($this->name,"modifier",[
            "modify"=>true,
            "name"=>$this->name,
            "type"=>"change",
            "command"=>" CHANGE {$name} "
        ]);
    }

    public function drop($column)
    {
        $this->name = $column;
        $validator =  new SchemaValidator(self::$table);
        if($validator->hasTable() && $validator->hasColumn($column))
        {
            $this->processRequest($this->name,"modifier",[
            "name"=>$column,
            "type"=>"drop",
            "command"=>" DROP COLUMN {$column} ",
            "modify"=>false
            ]);
        }
        return $this;
    }

    public function add()
    {
        if($this->ValidateTable($this->name) === false){
            $name = $this->name;
            $actions = [
                "modifier"=>true,
                "name"=>$this->name,
                "type"=>"add",
                "command"=>" ADD "];
            $this->processRequest($this->name,"modifier",$actions);
        }
        else
        {
            // self::unsetParams($this->name);
            $this->modify();
        }
    }

}
