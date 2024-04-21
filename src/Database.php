<?php

namespace Lazarus\LazarusDb;
use PDO;

class Database
{
    private $type;
    private $hostname;
    private $username;
    private $password;
    private $dbname;
    private $key;
    private $config = Database;

    // Global variables
    public  $version = "1.0";
    public  $filename = __FILE__;

    public function __construct()
    {

        return file_exists($this->config) ? $this->OpenConnection() : $this->FileNotFound();
      
    }

    public function FileNotFound()
    {
        echo "file ". Database . " Not Found";
    }
    // Set Default Config to Database;

    private function OpenConnection()
    {
        /**
         * this area needs cleaning up a littlet
         */
        $this->LoadConfig();
        try {
           new PDO($this->dsn(),$this->Username(),$this->Password(),$this->Options());
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }   
    }


    public function LoadConfig()
    {
        // Get the File Path Extention for the config file.
        $ext = pathinfo($this->config, PATHINFO_EXTENSION);
       
        // Detect which file is being used
        if($ext=="php")
        {
        include($this->config);
        $this->type = $type;
        $this->hostname = $hostname;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
        }
        elseif($ext=="ini")
        {
            $this->key = [];
        // Get Ini File using Constant
        $file = parse_ini_file($this->config);

        foreach($file as $key => $ini)
        {
             $this->key[$key] = $ini;
        }
        $this->type = $this->key['type'];
        $this->hostname = $this->key["hostname"];
        $this->username = $this->key["username"];
        $this->password = $this->key["password"];
        $this->dbname = $this->key["dbname"];
       //  Set individual Values
       return $this->key;
        }
    }

    public function Options()
    {
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_BOTH,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        return $options;
    }






}
