<?php
namespace LazarusPhp\LazarusDb\CoreFiles;

use Exception;
use LazarusPhp\LazarusDb\CoreFiles\Database;
use PDO;
use PDOException;

abstract class SchemaCore extends Database
{

    protected $data = [];
    protected $query = "";
    protected $errors = [];
    protected $name;
    protected static $table;
    // Sql Statement
    protected static  $sql;

    // Constructor

    public function __construct()
    {
        parent::__construct();
    }


// Check if chain exisit;
    protected function validChain()
    {
        if(!empty($this->query))
        {
            trigger_error("Previous Chained query has not been stored");
        }
        return true;
    }

    


    protected function save(string $sql = "")
    {
       $sql = !empty($sql) ? $sql : self::$sql; 

       try {
           $stmt = $this->prepare($sql);
           $stmt->execute();
           self::$sql = "";
           return $stmt;
       } catch (PDOException $e) {
           echo $e->getMessage();
       }
   }
   

}