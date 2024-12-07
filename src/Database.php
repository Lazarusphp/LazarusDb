<?php

namespace LazarusPhp\DatabaseManager;


use PDO;
use PDOException;

abstract class Database extends DbConfig
{
    // public $config;
    protected $config = [];
    protected $sql;
    protected  $connection;
    protected  $is_connected = false;
    protected $stmt;


    // Params
    protected  $param = array();
    // Db Credntials

    private  $type;
    private  $hostname;
    private  $username;
    private  $password;
    private  $dbname;


    public function __construct()
    {
            self::loadConfig();
     
            $this->config = [
                "type"=>self::getType(),
                "hostname"=>self::getHostname(),
                "username" => self::getUsername(),
                "password" => self::getPassword(),
                "dbname"=>self::getDbName(),
            ];

        try {
            // Manage Credentials
            if ($this->is_connected !== true) {
                $this->is_connected = true;
                $this->connection = new PDO($this->dsn(), $this->config["username"], $this->config["password"], $this->options());
            }
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }

    }

    public function connect():mixed
    {
        return $this->connection;
    }
    public function __destruct()
    {
        $this->connection = null;
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
        return $this->config["type"] . ":host=" . $this->config["hostname"] . ";dbname=" . $this->config["dbname"];
    }
}
