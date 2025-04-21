<?php
namespace LazarusPhp\LazarusDb;
use LazarusPhp\LazarusDb\CoreFiles\Database;

class Connection 
{

    // Set the Persistant connection property.
    protected static $isPersistant = false;

    // Set the path property
    private static $path;
    protected static $isConnected;
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

    // End Static Setters and Getters.

    public static function file(string $path)
    {
        self::$path = $path;
    }


    
    public static function activate()
    {

        // Detect the file exists.
        (!empty(self::$path) && file_exists(self::$path) && is_readable(self::$path)) ? include(self::$path) : false;
        
        isset($type) ? self::set("type", $type) : self::set("type", $_ENV["type"]);
        isset($hostname) ? self::set("hostname", $hostname) : self::set("hostname", $_ENV["hostname"]);
        isset($username) ? self::set("username", $username) : self::set("username", $_ENV["username"]);
        isset($password) ? self::set("password", $password) : self::set("password", $_ENV["password"]);
        isset($dbname) ? self::set("dbname", $dbname) : self::set("dbname", $_ENV["dbname"]);

    }

}
