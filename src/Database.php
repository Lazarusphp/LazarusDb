<?php

namespace LazarusPhp\DatabaseManager;


use PDO;
use PDOException;
use RuntimeException;

abstract class Database extends Connection
{
    // public $config;
    protected $sql;
    protected  $connection;
    protected  $is_connected = false;
    protected $stmt;
    protected $name;
    public $lastId;


    // Params
    protected  $param = array();
    protected $params;
    // Db Credntials

    public function __construct()
    {
            // self::bindProperties();
            $this->connect();
    }

    // Instantiate the Initial Connection
    private function connect()
    {
        try {
            // Manage Credentials
            if ($this->is_connected !== true) {
                $this->is_connected = true;
                $this->connection = new PDO($this->dsn(),self::returnBind("username"), self::returnBind("password"), $this->options());
            }
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    // get Connection
    protected function getConnection()
    {
        if($this->is_connected){
        return $this->connection;
        }
        else
        {
            throw new RuntimeException("Failed to get Database Connection");
        }
    }

    // Begin transaction
    protected function beginTransaction()
    {
        $this->getConnection()->beginTransaction();
    }

    // Commit transactoin
    protected function commit()
    {
        $this->getConnection()->commit();
    }

    // RollBack a transaction if failed
    protected function rollback()
    {
        return $this->getConnection()->rollback();
    }

    // Set Prepare Statement
    protected function prepare(string $sql)
    {
        return $this->connection->prepare($sql);
    }

    // Build and process the Database Query
    public function save(?string $sql = null, $array = []): mixed
    { 
        !is_null($sql) ? $this->sql = $sql : false;
        // Get the Params
        if (!empty($array)) $this->param = $array;
        // Check there is a connection
        try {
            $this->stmt = $this->prepare($this->sql);
            if (!empty($this->param)) $this->bindParams();
            $this->param = [];
            $this->beginTransaction();
            $this->stmt->execute();
            $this->lastId();
            $this->commit();

            return $this->stmt;
        } catch (PDOException $e) {
            $this->rollback();
            throw $e;
        }
    }

    // Return the last id the inserted value;

       
    public function lastId()
    {
        $this->lastId = $this->getConnection()->lastInsertId();
    }

    // Param Binding

    protected function bindParams(): void
    {
        if (!empty($this->param)) {
            // Prepare code
            foreach ($this->param as $key => $value) {
                $type = $this->getParamType($value);
                $this->stmt->bindValue($key, $value, $type);
            }
        }
    }

    // Get the Param Type
    protected function getParamType($value)
    {
        switch ($value) {
            case is_bool($value):
                return PDO::PARAM_BOOL;
            case is_null($value):
                return PDO::PARAM_NULL;
            case is_int($value):
                return PDO::PARAM_INT;
            case is_string($value):
                return PDO::PARAM_STR;
            default;
                break;
        }
    }

    // Dsn Options

    
    public function options():array
    {
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        return $options;
    }

    private function dsn():string
    {
        return self::returnBind("type") . ":host=" . self::returnBind("hostname") . ";dbname=" . self::returnBind("dbname");
    }
}
