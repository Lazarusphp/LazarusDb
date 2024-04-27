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
    public static function GenerateQuery(string $sql,)
    {

            self::$stmt = self::$connection->prepare($sql);
            // Using ParamBindiner
            self::parambinder();
            self::$stmt->execute();
            return self::$stmt;
            // Check if parameters array is not empty
    
    }

    public static function parambinder($array=null)
    {
       self::$param = array_combine(self::$paramkey, self::$paramvalue);
        // print_r($this->param);
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