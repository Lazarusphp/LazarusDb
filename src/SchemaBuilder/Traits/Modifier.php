<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder\Traits;

use LazarusPhp\LazarusDb\TableManagement\TableControl;
use LazarusPhp\LazarusDb\SchemaBuilder\Schema;
use LazarusPhp\LazarusDb\SchemaBuilder\SchemaActions;

trait Modifier
{
   
    private $oldname = "";


    public function modify()
    {
        $this->processRequest($this->name,"modifier",[
            "command"=>" MODIFY ","modifiable"=>true,
        ]);
    }

    public function rename($name)
    {
        $this->oldname = $name;
        return $this;
    }

    public function change()
    {
        $name  = ($this->oldname || !empty($this->oldname)) ? $this->oldname : $this->name;

        $name;
        $this->processRequest($this->name,"modifier",[
            "command"=>" CHANGE {$name} ","changeable"=>true 
        ]);
    }

    public function drop($column)
    {
        $this->name = $column;
        if(self::tableControl()->hasTablebyColumn($column)){
       
            $this->processRequest($this->name,"modifier",[
            "command"=>" DROP COLUMN {$column} ",
        ]);
    }
    }
}
