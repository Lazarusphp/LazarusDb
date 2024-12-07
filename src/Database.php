<?php

namespace LazarusPhp\DatabaseManager;
use PDO;
use PDOException;

abstract class Database extends DbConfig
{
    // public $config;
    
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
            self::returnConfig();
     
            $this->type = self::getType();
            $this->hostname = self::getHostname();
            $this->username = self::getUsername();
            $this->password = self::getPassword();
            $this->dbname = self::GetDbName();


        try {
            // Manage Credentials
            if ($this->is_connected !== true) {
                $this->is_connected = true;
                $this->connection = new PDO($this->dsn(), $this->username, $this->password, $this->Options());
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


    public function Options():array
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
        return $this->type . ":host=" . $this->hostname . ";dbname=" . $this->dbname;
    }
}
