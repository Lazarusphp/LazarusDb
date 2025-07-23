<?php
namespace LazarusPhp\LazarusDb\SchemaBuilder\Traits;
use LazarusPhp\LazarusDb\SchemaBuilder\Schema;
use LazarusPhp\LazarusDb\SchemaBuilder\SchemaActions;
use LazarusPhp\LazarusDb\TableManagement\TableControl;

trait Nullable
{

    protected $null = [];
    private $nullvalue;

   
    
    public function nullable()
    {
      
        $this->processRequest($this->name,"nullable",[
            "isActive" => true,
            "command"=>" NULL "
        ]);    
        return $this;
    }

    public function notNullable()
    {
         $this->processRequest($this->name,"nullable",[
            "command"=>" NOT NULL "
        ]);
        return $this;
    }
}