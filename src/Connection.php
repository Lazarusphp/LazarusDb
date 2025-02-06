<?php

namespace LazarusPhp\DatabaseManager;


use PDO;
use PDOException;

abstract class Connection extends Database
{
    // public $config;
    protected $config = [];
    protected $sql;
    protected  $connection;
    protected  $is_connected = false;
    protected $stmt;


    // Params
    protected  $param = array();
    protected $params;
    // Db Credntials

    public static $instance = false;
    private  $type;
    private  $hostname;
    private  $username;
    private  $password;
    private  $dbname;


    public static function open()
    {
        if(!self::$instance)
        {
            self::$instance = true;
            return new self();
        }
    }


    public function __construct()
    {
            self::bindProperties();
     
            $this->config = [
                "type"=>self::getType(),
                "hostname"=>self::getHostname(),
                "username" => self::getUsername(),
                "password" => self::getPassword(),
                "dbname"=>self::getDbName(),
            ];

            $this->connect();
    }

    private function connect()
    {
        
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
        return $this->config["type"] . ":host=" . $this->config["hostname"] . ";dbname=" . $this->config["dbname"];
    }
}
