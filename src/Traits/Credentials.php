<?php

namespace Lazarus\LazarusDb\Traits;

trait Credentials
{
    
    /**
     * Credential Management 
     * These Are Binded by the DbControl File Located at /Lazarus/LazarusDb/Interfaces
     * Removing or modifying these  function without understanding there useage will break the system
     * and cause it not to work correctly.
     *
     * @property string $type
     * @property string $hostname
     * @property string $username
     * @property string $password
     * @property string $dbname
     * 
     * @method mixed Type();
     * @method mixed Hostname();
     * @method mixed Username();
     * @method mixed Password();
     * @method mixed Dbname();
     * 
     * @method mixed Dsn();
     * 
     */
    
     private function Type()
     {
         if(empty($this->type)){
                 echo "Invalid File type maybe empty";
         }
         else
         {
             return $this->type;
         }
 }
 
     private function Hostname()
     {
         return $this->hostname;
     }
 
     private function Username()
     {
         return $this->username;
     }
 
     private function Password()
     {
         return $this->password;
     }
 
     private function Dbname()
     {
         return $this->dbname;
     }
 
     private function Dsn()
     {
          return $this->Type().":host=".$this->Hostname().";dbname=".$this->Dbname();
     }

    public function parsephp()
    {
        include($this->config);
        $this->type = $type;
        $this->hostname = $hostname;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
    }

    public function parseini()
    {
        $this->key = [];
        // Get Ini File using Constant
        $file = parse_ini_file($this->config);

        foreach($file as $key => $ini)
        {
             $this->key[$key] = $ini;
        }
        $this->type = $this->key['type'];
        $this->hostname = $this->key["hostname"];
        $this->username = $this->key["username"];
        $this->password = $this->key["password"];
        $this->dbname = $this->key["dbname"];
       //  Set individual Values
       return $this->key;
    }

}
?>