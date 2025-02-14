<?php
namespace LazarusPhp\DatabaseManager;
use App\System\Classes\Required\CustomErrorHandler;
use LazarusPhp\DatabaseManager\ConfigWriters\PhpWriter;
use LazarusPhp\DatabaseManager\Interfaces\ConfigInterface;
use LazarusPhp\SecurityFramework\EncryptionCall;

class Database
{

    private static $config = [];

    protected static function set($name, $value)
    {
            self::$config[$name] = $value;
    }

    protected static function get($name)
    {
            return self::$config[$name];
    }


        public static function DetectFileType($filename)
        {
            return pathinfo($filename,PATHINFO_EXTENSION);
        }
    

    protected static function bindProperties($type="",$hostname="",$username="",$password="",$dbname=""):void
    {
        self::set("type",$type);
        self::set("hostname",$hostname);
        self::set("username", $username);
        self::set("password", $password);
        self::set("dbname", $dbname);
    }

    public static function instantiate(string|array $param = "")
    {
        if(empty($param))
        {
            self::bindProperties($_ENV["type"], $_ENV["hostname"], $_ENV["username"], $_ENV["password"], $_ENV["dbname"]);
        }
        elseif(is_array($param))
        {
            self::bindProperties($param["type"],$param["hostname"],$param["username"],$param["password"],$param["dbname"]);
        }
        elseif(file_exists($param))
        {   
                include_once($param);
          }
        else
        {
            trigger_error("Error Occurred : File or array data not found");
        }

        

    }

    // public static function instantiate(string $filename, array $class = [PhpWriter::class]):void
    // {  
    //     // Override $key
    //     self::$filename = $filename;
    //     if(is_array($class))
    //     {
    //     self::bindClass($class);
    //     }
    // }



    protected static function getType()
    {
        return self::$config["type"];
    }

    protected static function getHostname()
    {
        return self::$config["hostname"];
    }

    protected static function getUsername()
    {
        return self::$config["username"];
    }

    protected static function getPassword()
    {
        return self::$config["password"];
    }

    protected static function getDbName()
    {
        return self::$config["dbname"];
    }

 


}
