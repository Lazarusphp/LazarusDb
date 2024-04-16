<?php

namespace Lazarus\LazarusDb\Traits;

trait Credentials
{
    
    public function type()
    {
        return $this->type;
    }

    public function Hostname()
    {
        return $this->hostname;
    }

    public function Username()
    {
        return $this->username;
    }

    public function Password()
    {
        return $this->password;
    }

    public function Dbname()
    {
        return $this->dbname;
    }

    private function dsn()
    {
        
         return $this->type().":host=".$this->Hostname().";dbname=".$this->Dbname();
         
    }

}
?>