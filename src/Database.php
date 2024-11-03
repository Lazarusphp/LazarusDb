<?php

namespace LazarusPhp\DatabaseManager;
use LazarusPhp\DatabaseManager\Traits\DbQueries;

use PDO;
use PDOException;

abstract class Database extends DbConfig
{
    public $config;
    
    private $sql;
    private  $connection;
    public  $is_connected = false;
    private $stmt;


    // Params
    private  $param = array();
    // Db Credntials

    private  $type;
    private  $hostname;
    private  $username;
    private  $password;
    private  $dbname;

    use DbQueries;

    public function __construct()
    {
            self::callConfig();
     
            $this->type = self::GetType();
            $this->hostname = self::GetHostname();
            $this->username = self::GetUsername();
            $this->password = self::GetPassword();
            $this->dbname = self::GetDbName();


        try {
            // Manage Credentials
            if ($this->is_connected !== true) {
                $this->is_connected = true;
                $this->connection = new PDO($this->Dsn(), $this->username, $this->password, $this->Options());
            }
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }


    }
    

    public function connect()
    {
        return $this->connection;
    }
    public function __destruct()
    {
    }

    public function CloseDb()
    {
        $this->connection = null;
    }

    public function sql($sql,$params=null)
    {
        $this->sql = $sql;
        !is_null($params) ?  $this->param = $params : false;
        return $this;
    }
    



    public function Options()
    {
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        return $options;
    }

    private function Dsn()
    {
        return $this->type . ":host=" . $this->hostname . ";dbname=" . $this->dbname;
    }
}
