<?php
namespace LazarusPhp\DatabaseManager;

use Exception;
use PDO;
use PDOException;
use LazarusPhp\DatabaseManager\CoreFiles\Database;

class QueryBuilder extends Database
{

    // Set Current Table Name
    public $table;
    public $where = [];
    // Set Current Modifier
    private $currentModifier;
    // Supported Modifiers
    private $supportedModifiers = ["read", "update", "delete"];
    // Set save Status
    public $saveStatus = false;
    // Set the Sql path;
    public $sql;

    protected $stmt;
    public $lastId;


    // Params
    protected  $param = [];

    public function __construct(?string $table = null)
    {
        // Call the parent constructor to get the connection
        parent::__construct();
        // table can now also be set in the constructor
        $table ? $this->table = $table : false;
    }


    public function __set($name, $value)
    {
        $this->param[$name] = $value;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->param)) {
            return $this->param[$name];
        }
    }
    

    public function save(?string $sql = null, $array = []): mixed
    { 
        !is_null($sql) ? $this->sql = $sql : false;
        // Get the Params
        if (!empty($array)) $this->param = $array;
        // Check there is a connection
        try {
            $this->stmt = $this->prepare($this->sql);
            if (!empty($this->param)) $this->bindParams();
            $this->beginTransaction();
            $this->stmt->execute();
            $this->lastId = $this->lastId();
            $this->commit();
            return $this->stmt;
        } catch (PDOException $e) {
            $this->rollback();
            throw $e->getMessage();
        }
    }


    
}
