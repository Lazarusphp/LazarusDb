<?php
namespace LazarusPhp\DatabaseManager;
use App\System\Classes\Required\CustomErrorHandler;
use LazarusPhp\DatabaseManager\ConfigWriters\PhpWriter;
use LazarusPhp\DatabaseManager\Interfaces\ConfigInterface;
use LazarusPhp\DatabaseManager\Traits\Encryption;

class DbConfig
{

    private static $config;
    private static  $type;
    private static $username;
    private static $hostname;
    private static $dbname;
    private static $password;
    private static ConfigInterface $configInterface;

    // Get the filename;
    private static $filename;
    private static $class;
   

    // Start again here


use Encryption;

    private static function bindClass(array $class):void
    {
        class_exists($class[0]) ? self::$configInterface = new $class[0](self::$filename) : trigger_error("Class Does not exist");    
    }

    private static function BindProperties():void
    {
        self::$type = self::$configInterface->setType();
        self::$hostname = self::$configInterface->setHostname();
        self::$username = self::$configInterface->setUsername();
        self::$password = self::$configInterface->setPassword();
        self::$dbname = self::$configInterface->setDbname();
    }

    public static function Load(string $filename, array $class = [PhpWriter::class],$key):void
    {
        // Override $key
        self::$key = $key;
        self::$filename = $filename;
        if(is_array($class))
        {
        self::bindClass($class);
        }
        
    }

    protected static function returnConfig()
    {
        return self::BindProperties();
    }

    protected static function GetType()
    {
        return self::decryptValue(self::$type);
    }

    protected static function GetHostname()
    {
        return self::decryptValue(self::$hostname);
    }

    protected static function GetUsername()
    {
        return self::decryptValue(self::$username);
    }

    protected static function GetPassword()
    {
        return self::decryptValue(self::$password);
    }

    protected static function GetDbName()
    {
        return self::decryptValue(self::$dbname);
    }


}
