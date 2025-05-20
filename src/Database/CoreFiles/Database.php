<?php

namespace LazarusPhp\LazarusDb\Database\CoreFiles;

use LazarusPhp\LazarusDb\Database\Connection;
use PDO;
use PDOException;
use RuntimeException;

abstract class Database extends Connection
{

    // Class implementation goes here

    protected $stmt;
    public $lastId;

    public function __construct()
    {
        $this->connect();
    }




    private function getConnection()
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

    // Set Prepare Statement using prepart
    protected function prepare(string $sql)
    {
        return $this->getConnection()->prepare($sql);
    }

    // Set prepare statements using query
        protected function query(string $sql)
    {
        return $this->getConnection()->query($sql);
    }
    
    // Return the last id the inserted value;

       
    public function lastId()
    {
        return $this->getConnection()->lastInsertId();
    }


}