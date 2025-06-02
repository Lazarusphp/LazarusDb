<?php

namespace LazarusPhp\LazarusDb\SharedAssets\Traits;
use LazarusPhp\LazarusDb\SchemaBuilder\Schema;

trait TableControl
{
    // Trait methods and properties go here


    public function hasTable()
    {
        $query = "SHOW TABLES LIKE '" . Schema::getTable(). "'";
        $result = $this->query($query);
        if ($result && $result->rowCount() >= 1) {
           return true;
        }
        else
        {
            return false;
        }
    }

    protected function array_exists($key,$array)
    {
        if(array_key_exists($key,$array))
        {
            return true;
        }
        return false;
    }

    public function allColumns($fieldname)
    {
        $query = "SELECT * ";
        $query .= " FROM INFORMATION_SCHEMA.COLUMNS ";
        $query .= " WHERE TABLE_SCHEMA='".$_ENV['dbname']."' ";
        $query .= " AND TABLE_NAME = '".self::$table."'";
        $query .= " AND COLUMN_NAME='{$fieldname}' ";

        echo $query;
        // Use a direct PDO query to avoid interfering with self::$sql
        $stmt = $this->query($query);
        if ($stmt && $stmt->rowCount() > 0) {
            return $stmt->fetch();
        }
        return false;
    }

    public function hasColumns($field,$key,$value)
    {
        $fetch = $this->allColumns($field);     
        $constraint = strtoupper($key);
        if($fetch->$constraint === $value)
        {
            echo "WE found it $constraint $value";
        }
        else
        {
            echo "failed";
        }
        // return false;
    }


    

        public function isPrimary($value)
        {
            $query = "SELECT * FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA='".$_ENV['dbname']."' AND TABLE_NAME='".self::$table."' AND CONSTRAINT_NAME='PRIMARY'";
            $stmt = $this->query($query);
            if($stmt && $stmt->rowCount())
            {
                $result = $stmt->fetch();
                return $result->COLUMN_NAME === $value ? true : false;
            }
            return false;
        }

}