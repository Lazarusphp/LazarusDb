<?php

namespace LazarusPhp\DatabaseManager;


use PDO;
use PDOException;

abstract class Connection extends Database
{
    // public $config;
    protected $sql;
    protected  $connection;
    protected  $is_connected = false;
    protected $stmt;


    // Params
    protected  $param = array();
    protected $params;
    // Db Credntials

    public function __construct()
    {
            // self::bindProperties();
            $this->connect();
    }

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

    protected function close()
    {
        return $this->connection = null;
    }
   


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
