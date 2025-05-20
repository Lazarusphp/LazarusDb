<?php
namespace LazarusPhp\LazarusDb\Database;

use LazarusPhp\LazarusDb\QueryBuilder\QueryBuilder;
use PDO;
use PDOException;

class Connection 
{
    // Set the Persistant connection property.
    // Validate if connection is Live
    protected $is_connected = false;
    // Attach connection
    protected  $connection;
    // set config property
    private static $config = [];

    // Static setters and Getters
    protected static function set(string $name, int|string $value)
    {
            self::$config[$name] = $value;
    }

    protected static function bind(string $name)
    {
            return self::$config[$name];
    }
     protected function connect()
    {
        // check for Connection
        try {
            // Manage Credentials
            if(!$this->is_connected){
                $this->is_connected = true;
                $this->connection = new PDO($this->dsn(),self::bind("username"), self::bind("password"), $this->options());
            }
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    
    // Dsn Options

    
    protected function options():array
    {
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        return $options;
    }

    private function dsn():string
    {
        return self::bind("type") . ":host=" . self::bind("hostname") . ";dbname=" . self::bind("dbname");
    }



    /**
     * Activate
     *
     * @param boolean $isPersistant
     * @return void
     */
    public static function activate()
    {
        $type = $_ENV["type"] ?? null;
        $username = $_ENV["username"] ?? null;
        $hostname = $_ENV["hostname"] ?? null;
        $password = $_ENV["password"] ?? null;
        $dbname = $_ENV["dbname"] ?? null;
        // Detect the file exists.
        isset($type) ? self::set("type", $type) : trigger_error("Connection driver invalid or missing");
        isset($hostname) ? self::set("hostname", $hostname) : trigger_error("Hostname invalid or missing");
        isset($username) ? self::set("username", $username) : trigger_error("Username invalid or missing");
        isset($password) ? self::set("password", $password) : trigger_error("Password invalid or missing");
        isset($dbname) ? self::set("dbname", $dbname) : trigger_error("Database name invalid or missing");
        
    }

    public static function onConnection($value)
    {
        // Return the called class for chainloading
        self::set("dbname",$value);
        return new static;
    }

    protected static function resetConnection()
    {
        self::set("dbname",$_ENV["dbname"]);
    }
}
