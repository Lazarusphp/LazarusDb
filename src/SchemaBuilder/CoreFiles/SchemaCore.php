<?php
namespace LazarusPhp\LazarusDb\SchemaBuilder\CoreFiles;

use Exception;
use LazarusPhp\LazarusDb\Database\CoreFiles\Database;
use LazarusPhp\LazarusDb\SchemaBuilder\Schema;
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
    protected $tableControl;
    protected static $table;
    // Sql Statement

    public static $migrationFailed = [];
    public static $migrationError = [];
    protected static $sql = "";

    // Constructor

    public function __construct()
    {
        $this->tableControl = new TableControl(self::$table);
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
            Schema::$migrationError[] = $e->getMessage();
            return false;
       }
   }
   

}