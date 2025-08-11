<?php
namespace LazarusPhp\LazarusDb\SchemaBuilder\CoreFiles;

use Exception;
use LazarusPhp\LazarusDb\Database\CoreFiles\Database;
use LazarusPhp\LazarusDb\SchemaBuilder\Schema;
use LazarusPhp\LazarusDb\SchemaBuilder\SchemaErrors;
use LazarusPhp\LazarusDb\TableManagement\TableControl;
use PDO;
use PDOException;

abstract class SchemaCore extends Database
{

    protected $data = [];
    protected $errors = [];
    // Possibly Move Name to Ta
    protected $name;
    protected static $query = [];
    protected static $table;
    // Sql Statement

    protected static $sql = "";

    // Constructor

    public function __construct()
    {
        parent::__construct();
    }

    protected function save(string $sql = "")
    {
       $sql = !empty($sql) ? $sql : self::$sql; 
       try {
           $stmt = $this->prepare($sql);
           $stmt->execute();
           return true;
       } catch (PDOException $e) {
            SchemaErrors::generate("Failed to save",["reason",$e->getMessage()]);
            return false;
       }
   }
   

}