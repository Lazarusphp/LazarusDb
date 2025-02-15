<?php
namespace LazarusPhp\DatabaseManager;
use App\System\Classes\Required\CustomErrorHandler;
use LazarusPhp\DatabaseManager\ConfigWriters\PhpWriter;
use LazarusPhp\DatabaseManager\Interfaces\ConfigInterface;
use LazarusPhp\SecurityFramework\EncryptionCall;

class Connection
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

    public static function close()
    {
        self::$config = [];
    }

    public static function instantiate(string|array $param = "")
    {
        if($param === "env")
        {
            self::bindProperties($_ENV["type"], $_ENV["hostname"], $_ENV["username"], $_ENV["password"], $_ENV["dbname"]);
        }
        elseif(is_array($param))
        {
            self::bindProperties($param["type"],$param["hostname"],$param["username"],$param["password"],$param["dbname"]);
        }
        elseif(file_exists($param))
        {   
            if(self::DetectFileType($param) === "php"){
                include_once($param);
                self::bindProperties($type,$hostname,$username,$password,$dbname);
            }
            else
            {
                trigger_error("File detected is not a php file");
            }
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



    protected static function returnBind($name)
    {
        return self::$config[$name];
    }

}
