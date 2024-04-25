<?php
namespace Lazarus\LazarusDb\Traits;

use PDOException;
use PDO;

trait SqlManager
{

    public static $sql;
    private static $stmt;


    // Note: Need to add support for bind values;
    public static function GenerateQuery(string $sql, $array = null)
    {
            self::$stmt = self::$connection->prepare($sql);
            // Need to add Param Binding
            self::$stmt->execute();
            return self::$stmt;
    }
        

    public static function BindValues($array)
    {
        // !is_null($array) ? $this->param = $array :  $this->param = array_combine($this->paramkey, $this->paramvalue);
        if (!empty($array)) {       // Prepare code
            foreach ($array as $key => $value) {
                //    Execute the loop and bind the parameters
                 self::$stmt->bindValue(":".$key, $value);
            }
        }
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