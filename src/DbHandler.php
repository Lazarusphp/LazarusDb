<?php
namespace LazarusPhp\DatabaseManager;
use App\System\Core;
use PDO;
use PDOException;
use Exception;
class DbHandler
{

    public static $sql;
    private static $config;
    private static $instance;
    private static $connection;
    private static $stmt;
    // Flags;
    public static $is_connected = false;
    private static $setParamArray;

    // Params
    private static $param = array();
    //private static $paramkey;
    //private static $paramvalue;

    // Db Credntials

    private static $type;
    private static $hostname;
    private static $username;
    private static $password;
    private static $dbname;


    private function __construct()
    {
    }

    final public static function Boot()
    {
        if(!isset(self::$instance)) {        
            self::$config = Core::GenerateRoot()."/Config.php";
            if(is_file(self::$config) && file_exists(self::$config))
            {
                include(self::$config);
                self::$type = $type;
                self::$hostname = $hostname;
                self::$username = $username;
                self::$password = $password;
                self::$dbname = $dbname;
            } else {
                throw new \Exception("Failed to load config", 1);
            }
            // Allow for Singleton Instance
            $class = get_called_class();
            self::$instance = $class;
        }
        return self::$instance;
    }

    final public static function Connect(): bool
    {
        try {
            // Manage Credentials
            if(self::$is_connected !== true) {
                self::$is_connected = true;
                self::$connection = new PDO(self::Dsn(), self::$username, self::$password, self::Options());
            }
            return true;
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
        // Connect to the database
    }

    final public static function BindParams()
    {

        // self::$param = array_combine(self::$paramkey, self::$paramvalue);
        // self::Connect();
        if (!empty(self::$param)) {
            // Prepare code
            foreach (self::$param as $key => $value) {
    
                self::$stmt->bindValue($key, $value);
            }
        }
    }

    public function Save()
    {
        $stmt = self::GenerateSql(self::$sql);
        return $stmt;
    }

    final public static function AddParams($key, $value) {
        self::$param[$key] = $value;
    }

    public static function GenerateSql(array $array = [])
    {
        if(!empty($array)) self::$param = $array;
        // Check there is a connection
        if(self::Connect()) {
            try {
                self::$stmt = self::$connection->prepare(self::$sql);
                if(!empty(self::$param)) self::BindParams();
                self::$stmt->execute();
                return self::$stmt;      
            } catch(PDOException $e) {
                echo $e->getMessage() . $e->getCode();
            }
        }
    }

    public static function RowCount()
    {
        $stmt = self::GenerateSql();
        return $stmt->RowCount();
    }

    public static function Sql($sql)
    {
        return self::$sql = $sql;
        // is_null($array) ? self::$setParamArray = null : self::$setParamArray = $array;
    }

    // Fetch one result
    public static function One($type= PDO::FETCH_OBJ)
    {
        $stmt = self::GenerateSql();
        return $stmt->fetch($type);
    }

    // Fetch ALl Result
    public static function All($type = PDO::FETCH_OBJ)
    {
        $stmt = self::GenerateSql();
        return $stmt->fetchAll($type);
    }

    public static function LastId()
    {
        // self::Connect();
        
        return (self::Connect()) ? self::$connection->lastInsertId() : false;
    }

    // Database Handler Options
    public static function Options()
    {
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        return $options;
    }

    private static function Dsn()
    {
        return self::$type . ":host=" . self::$hostname . ";dbname=" . self::$dbname;
    }
}g