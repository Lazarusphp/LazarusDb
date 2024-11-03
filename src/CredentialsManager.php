<?php
namespace LazarusPhp\DatabaseManager;
use App\System\Classes\Required\CustomErrorHandler;
use LazarusPhp\DatabaseManager\Traits\Encryption;

class CredentialsManager
{

    private static $config;
    private static  $type;
    private static $username;
    private static $hostname;
    private static $dbname;
    private static $password;

    private static $key = ""; // Make sure to use a secure key
    private static $cipher = 'AES-256-CBC';


    // Filename;
    private static $ext;
    // Inject Config Interface.
    private static ConfigInterface $configInterface;

    // this will work as one or the other
    protected static $configClass = false;
    protected static $configFile = false;


    use Encryption;
    private function __construct()
    {
        CustomErrorHandler::Boot();
    }

    public static function configClass(array $class,$key=null,$cipher=null)
    {
        self::$key = $key ?? self::$key;
        self::$cipher = $cipher ?? self::$cipher;
        if(is_array($class))
        {
            if(class_exists($class[0]))
            {
                self::$configInterface = new $class[0]();
            }
        }
       $key =  self::encryptvalue(self::$configInterface->setHostname());
       echo $key;
      echo  self::decryptValue($key);

        self::$configClass = true;
    }
    
    public static function configFile($filename)
    {
        self::validFile($filename) ? self::$config = $filename : trigger_error("Config File $filename cannot be located.");

        self::$configFile = true;
    }


    // Validate that the file exists and $filename is a file.
    private static function validFile($filename):bool{
        if(file_exists($filename) && (is_file($filename)))
        {
            self::$ext = self::getExtention($filename);
            echo self::$ext;
            return true;
        }
        return false;
    }

    private static function getExtention($filename)
    {
        return pathinfo($filename,PATHINFO_EXTENSION);
    }


    protected static function DetectFormat()
    {
       
        $ext = self::getExtention(self::$config);
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

    protected static function LoadConfig()
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