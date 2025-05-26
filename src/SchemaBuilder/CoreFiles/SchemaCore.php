<?php
namespace LazarusPhp\LazarusDb\SchemaBuilder\CoreFiles;

use Exception;
use LazarusPhp\LazarusDb\Database\CoreFiles\Database;
use PDO;
use PDOException;

abstract class SchemaCore extends Database
{

    protected $data = [];
    protected $errors = [];
    protected $name;
    protected $query = [];
    public $method = [];
    protected static $table;
    // Sql Statement

    public static $migrationFailed = false;
    public static $migrationError = [];
    protected static  $sql = "";

    // Constructor

    public function __construct()
    {
        parent::__construct();
    }

    public static function migrationFailed()
    {
        if(self::$migrationFailed)
        {
            return true;
        }
        else
        {
            return false;
        }
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