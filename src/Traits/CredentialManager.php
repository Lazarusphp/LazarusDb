<?php

namespace Lazarus\LazarusDb\Traits;

trait CredentialManager
{

    private static $passport = [];

    private static $hostname;
    private static $type;
    private static $username;
    private static $dbname;
    private static $password;

    private static $access;


    /**
     * Credential Management 
     * These Are Binded by the DbControl File Located at /Lazarus/LazarusDb/Interfaces
     * Removing or modifying these  function without understanding there useage will break the system
     * and cause it not to work correctly.
     *
     * @property string $type
     * @property string $hostname
     * @property string $username
     * @property string $password
     * @property string $dbname
     * @property mixed $passport
     * 
     * @method mixed function Passport()
     * 
     * @method mixed Dsn();
     * 
     */

    public static function passport()
    {
        include_once(self::$config);
        // Loop through the Values
        $values = [
            "type" => $type,
            "hostname" => $hostname,
            "username" => $username,
            "password" => $password,
            "dbname" => $dbname
        ];


        foreach ($values as $key => $value) {
            
            if(empty($key))
            {
                echo "$key is empty";
                self::$access = false;
            }
            else
            {
                self::$access = true;
                self::$passport[$key] = $value;
            }
          
        }

        return self::$passport;
    }

    private function Dsn()
    {
        return self::$passport["type"] . ":host=" . self::$passport["hostname"] . ";dbname=" . self::$passport["dbname"];
    }
}
