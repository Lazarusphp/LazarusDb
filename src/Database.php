<?php

namespace LazarusPhp\DatabaseManager;


use PDO;
use PDOException;
use RuntimeException;

abstract class Database extends Connection
{
    // public $config;
    protected  $connection;
    protected  $is_connected = false;
    protected $stmt;
    public $lastId;


    // Params
    protected  $param = [];
    // Db Credntials

    public function __construct()
    {
            // self::bindProperties();
            $this->connect();
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
        try {
            $this->getConnection()->beginTransaction();
        } catch (PDOException $e) {
            throw new RuntimeException("Failed to begin transaction: " . $e->getMessage(), (int)$e->getCode());
        }
    }
        // $this->getConnection()->beginTransaction();

    // Commit transactoin
    protected function commit()
    {
        try {
            $this->getConnection()->commit();
        } catch (PDOException $e) {
            throw new RuntimeException("Failed to commit transaction: " . $e->getMessage(), (int)$e->getCode());
        }
    }

    // RollBack a transaction if failed
    protected function rollback()
    {
        try {
            $this->getConnection()->rollback();
        } catch (PDOException $e) {
            throw new RuntimeException("Failed to rollback transaction: " . $e->getMessage(), (int)$e->getCode());
        }
    }

    // Set Prepare Statement
    protected function prepare(string $sql)
    {
        return $this->connection->prepare($sql);
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
