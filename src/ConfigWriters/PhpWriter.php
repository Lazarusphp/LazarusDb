<?php
namespace LazarusPhp\DatabaseManager\ConfigWriters;
use LazarusPhp\DatabaseManager\Interfaces\ConfigInterface;
use LazarusPhp\DatabaseManager\Traits\Encryption;

class PhpWriter implements ConfigInterface
{


    private $type;
    private $hostname;
    private $username;
    private $password;
    private $dbname;

 use Encryption;
    public function __construct($filename)
    {
        if($this->DetectFileType($filename) === "php"){
            if(file_exists($filename))
            {
                include($filename);
                $this->type = $type;
                $this->hostname = $hostname;
                $this->username = $username;
                $this->password = $password;
                $this->dbname = $dbname;
            }
            else
            {
                trigger_error("Error Could not Located file $file");
            }
        }
    }

    public function DetectFileType($filename)
    {
        return pathinfo($filename,PATHINFO_EXTENSION);
    }

    

    public function setType()
    {
        return self::encryptValue($this->type);
    }


    public function setHostname()
    {
        return self::encryptValue($this->hostname);
    }


    public function setUsername()
    {
        return self::encryptValue($this->username);
    }

    public function setPassword()
    {
        return self::encryptValue($this->password);
    }

    public function setDbname()
    {
        return self::encryptValue($this->dbname);
    }

}