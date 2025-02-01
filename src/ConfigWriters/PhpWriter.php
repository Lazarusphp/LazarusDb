<?php
namespace LazarusPhp\DatabaseManager\ConfigWriters;
use LazarusPhp\DatabaseManager\Interfaces\ConfigInterface;
use LazarusPhp\SecurityFramework\EncryptionCall;

class PhpWriter implements ConfigInterface
{
    private $type;
    private $hostname;
    private $username;
    private $password;
    private $dbname;
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
        else
        {
            trigger_error("Wrong File Type Loaded, Please Load a php File");
            exit();
        }
    }

    public function DetectFileType($filename)
    {
        return pathinfo($filename,PATHINFO_EXTENSION);
    }

    

    public function setType()
    {
        return EncryptionCall::encryptValue($this->type);
    }


    public function setHostname()
    {
        return EncryptionCall::encryptValue($this->hostname);
    }


    public function setUsername()
    {
        return EncryptionCall::encryptValue($this->username);
    }

    public function setPassword()
    {
        return EncryptionCall::encryptValue($this->password);
    }

    public function setDbname()
    {
        return EncryptionCall::encryptValue($this->dbname);
    }

}