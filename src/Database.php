<?php

namespace LazarusPhp\LazarusDb;

use LazarusPhp\LazarusDb\Traits\CredentialManager;
use App\System\BaseClasses\Files;
use LazarusPhp\LazarusDb\Traits\SqlManager;
use PDO;

class Database
{

    /**
     * 1 Need to find away of maing the self::$config secure and stored.
     * 2 Improve Overall function of Database Class
     */
    // CredentialTrait
    use CredentialManager;
    use SqlManager;

    private $key;

    private static $connection;
    private static $instance;
    private static $config;

    // Global variables
    public  $version = "1.0";
    public  $filename = __FILE__;


    // Set Default self::$config to Database;

    private function __construct()
    {
        $file = is_file(self::$config) && file_exists(self::$config) ? true : false;


        try {
            self::$connection =  new PDO(self::Dsn(), self::$passport["username"], self::$passport["password"], self::Options());
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
        
        // if($file == true)
        // {
        //     // Instantiate the connection
        //     self::$connection = $this->OpenConnection();
        // }
        // else
        // {
        //     exit("file not found or may not be a file format");
        // }
    
    }


    // Call the class as a singleton / Statically
    public static function Connect($config = null)
    {

        if (!is_null($config)) {
            // Verify if it is a file

            // Currently Script Relies on base Script Files::Verify
            /**
             * @property constant $config
             * @method mixed Files::VerifyFile($config)
             *              
             * */
            if (Files::VerifyFile($config)) {
                self::$config = $config;
                self::passport();
                $bool = true;
            }
            else
            {
                // Will Change exit to an error Log
                exit("Error: File Could Not be verified");
            }
            // End Verification

            // Start Instantiation
            if ($bool == true) {
                if (!isset(static::$instance)) {
                    // Call the currently Called class;
                    $class = get_called_class();
                    // Set the Static Instance.
                    static::$instance = new $class;
                }

                return static::$instance;

            } 
               // End Instantiation
            else {
                exit("Connection CLass Could not be instantiated");
            }
         

        } else {
            exit("Config Parameter Left Empty");
        }
    }


    public static function Options()
    {
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        return $options;
    }


}
