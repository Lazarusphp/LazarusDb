<?php
namespace LazarusPhp\DatabaseManager;
use App\System\Classes\Required\CustomErrorHandler;

class CredentialsManager
{

    private static $config;
    private static  $type;
    private static $username;
    private static $hostname;
    private static $dbname;
    private static $password;

    private function __construct()
    {
        CustomErrorHandler::Boot();
    }
    public static function DetectFormat()
    {
        $ext = pathinfo(self::$config,PATHINFO_EXTENSION);
       
        if($ext == "php")
        {
            include(self::$config);
            self::$type = $type;
            self::$hostname = $hostname;
            self::$username = $username;
            self::$password = $password;
            self::$dbname = $dbname;
            return true;
        }
        elseif($ext == "ini")
        {
            $ini = parse_ini_file(self::$config);
            foreach($ini as $key => $value)
            self::$type = $ini["type"];
            self::$hostname = $ini["hostname"];
            self::$username = $ini["username"];
            self::$password = $ini["password"];
            self::$dbname = $ini["dbname"];
            return true;
        }
        return false;
    }

    public static function SetConfig($config)
    {
        self::$config = $config;
    }

    public static function LoadConfig()
    {
        if(self::DetectFormat() == true)
        {
            return self::$config;
        }
        else{
            echo "Invalid Config File";
        }
    }
    public static function GetType()
    {
        return self::$type;
    }

    public static function GetHostname()
    {
        return self::$hostname;
    }

    public static function GetUsername()
    {
        return self::$username;
    }

    public static function GetPassword()
    {
        return self::$password;
    }

    public static function GetDbName()
    {
        return self::$dbname;
    }



}