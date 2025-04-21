<?php

namespace LazarusPhp\LazarusDb\CoreFiles;

use LazarusPhp\LazarusDb\Connection;
use PDO;
use PDOException;
use RuntimeException;

abstract class Database extends Connection
{
    // Class implementation goes here
    protected  $connection;
    protected  $is_connected = false;
    protected $stmt;
    public $lastId;

    public function __construct()
    {
        // check if connection is perisitant if so run $this connect
            $this->connect();
    }


    private function connect()
    {
        // check for Connection
        try {
            // Manage Credentials
            if(!$this->is_connected){
                $this->is_connected = true;
                $this->connection = new PDO($this->dsn(),self::bind("username"), self::bind("password"), $this->options());
            }
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

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
        return $this->getConnection()->lastInsertId();
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
        return self::bind("type") . ":host=" . self::bind("hostname") . ";dbname=" . self::bind("dbname");
    }
}