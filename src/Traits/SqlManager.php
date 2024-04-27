<?php
namespace LazarusPhp\LazarusDb\Traits;

use LazarusPhp\LazarusDb\Database;
use PDOException;
use PDO;
use PDOStatement;

use function SnorkelWeb\DBManager\OpenConnection;

trait SqlManager
{

    public static $sql;
    private static $stmt;


    // Dummy run
    public static $param;
    public static $paramvalue = [];
    public static $paramkey = [];

    // Note: Need to add support for bind values;
    public static function GenerateQuery(string $sql,array $array=null)
    {

           if(is_array(self::$paramkey) && is_array(self::$paramvalue) && is_null($array))
           {
             self::$param = array_combine(self::$paramkey, self::$paramvalue);
           }
           elseif(is_array($array))
           {
            self::$param = $array;
           }
           else
           {
            echo "An Error Occurred";
           }


            self::$stmt = self::$connection->prepare($sql);
        
            is_array(self::$param) ? self::parambinder() : false;
            self::$stmt->execute();
            return self::$stmt;
            // Check if parameters array is not empty
    
    }


 

    public static function parambinder()
    {

        // self::$param = array_combine(self::$paramkey, self::$paramvalue);
        if (!empty(self::$param)) {
            // Prepare code
            foreach (self::$param as $key => $value) {
                //    Execute the loop and bind the parameters
                self::$stmt->bindValue($key, $value);
            }
        }
    }

    public static function withParams($keys, $values) {
        // Store parameter keys and values in arrays
        self::$paramkey[] = $keys;
        self::$paramvalue[] = $values;
    }

// Count Rows
    public function RowCount()
    {
        return self::$stmt->rowCount();
    }

    // Fetch one result
    public function Fetch($type = PDO::FETCH_OBJ)
    {
        return self::$stmt->fetch($type);
    }

    // Fetch ALl Result
    public function Fetch_All($type = PDO::FETCH_OBJ)
    {
        return self::$stmt->fetchAll($type);
    }

    public static function LastId()
    {
        return self::$connection->lastInsertId();
    }


}