<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder\Traits;

use LazarusPhp\LazarusDb\TableManagement\TableControl;
use LazarusPhp\LazarusDb\SchemaBuilder\Schema;
use LazarusPhp\LazarusDb\SchemaBuilder\SchemaActions;
use LazarusPhp\LazarusDb\SchemaBuilder\SchemaErrors;
use LazarusPhp\LazarusDb\SchemaBuilder\SchemaValidator;
use LazarusPhp\LazarusDb\TableManagement\Table;

// Add a Drop type Enum Drop works as a modifier but will also be part of the other scripts
enum DropType:string
{
    case column = 'column';
    case index = 'index';
    case fk = 'fk';
    case unique = 'unique';
}

trait Modifier
{
   
    private $oldname = "";
    private $rename;


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
        }
    }

    public function rename($name)
    {
        $validator = new SchemaValidator(self::$table);
        $index = $validator->hasIndexes();

        foreach($index as $index)
        {
            if($index->COLUMN_NAME === $name)
            {
                SchemaErrors::generate("Cannot Rename Table", ["Reason Column $name Belongs to an index"]);
                break;
            }
        }

        if($validator->hasTable(self::$table) && !$validator->hasColumn($name))
        {
            SchemaErrors::generate("Cannot Rename Table", ["Reason Column $name doesnt exist"]);
                
        }
        $this->rename = true;
        $this->oldname = $name;
        return $this;
    }

    // Merge with Modify.
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

    // Delete this and move into its own file.
    public function drop(string|DropType $type,string $name)
    {
        $this->name = $name;
        $validator =  new SchemaValidator(self::$table);

        if (is_string($type)) {
        $type = DropType::from($type); // throws if invalid
        }

     

        if($type===DropType::column)
        {
               if(self::hasDataType($name))
            {
                SchemaErrors::generate("Cannot Drop Column",
                ["Reason"=>"Migration file : ".self::$table." containts a duplicate record for $name"]);
                return false;
            }

            if($validator->hasTable() && !$validator->hasColumn($name))
            {
                SchemaErrors::generate("Cannot Drop Column",["Reason"=>"Column : $name not found"]);
                return false;
            }
        }

        if($type === DropType::index)
        { 
            foreach(self::getParams() as $table => $props)
            {
                if(isset($props["indexes"]))
                {
                    
                    foreach($props["indexes"] as $column)
                    {
                        if($column["name"] !== $name)
                        {
                            SchemaErrors::generate("Cannot drop Index",["Reason"=>"Index Name doesnt exist","Index name"=> $column["name"]]);
                            return true;
                        }
                    }
               
                }
            }
            
        }

            $command = match($type)
        {
            DropType::column => "DROP COLUMN $name",
            DropType::index => "DROP INDEX $name",
            DropType::fk => "DROP FOREIGN KEY $name",
            DropType::unique => "DROP UNIQUE $name",
        };

        $types = match($type)
        {
            DropType::column => "column",
            DropType::index => "index",
            DropType::fk => "fk",
            DropType::unique => "unique",
        };


            $this->processRequest($this->name,"drop",[
            "name"=>$name,
            "type"=>$types,
            "command"=>$command,
            "modify"=>false
            ]);
        
        return $this;
    }

    // Add a new Column
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
