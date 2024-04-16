<?php

namespace Lazarus\LazarusDb;

use Core\System;
use Lazarus\LazarusDb\Traits\Credentials;
use PDO;

class Connection extends System
{

     use Credentials;


     public $version = "1.0";
     public $Package_name;
     private $hostname = "Martin";
     private $username;
     private $password;
     private $dbname;


     Public function __construct()
     {
          
     }

    
     

}