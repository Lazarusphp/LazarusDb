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

    private function validKeys($key)
    {
        $validKeys = ["COLUMN_NAME","TABLE_NAME","TABLE_SCHEMA","COLUMN_KEY","DATA_TYPE","IS_NULLABLE","COLUMN_DEFAULT","COLUMN_DEFAULT"];
        return in_array($key,$validKeys) ? true : false;
    }

    public function allColumns($fieldname,$all=false)
    {
        $query = "SELECT * ";
        $query .= " FROM INFORMATION_SCHEMA.COLUMNS ";
        $query .= " WHERE TABLE_SCHEMA='".$_ENV['dbname']."' ";
        $query .= " AND TABLE_NAME = '".self::$table."'";
        $query .= " AND COLUMN_NAME='{$fieldname}' ";
        // Use a direct PDO query to avoid interfering with self::$sql
        $stmt = $this->query($query);
        if ($stmt && $stmt->rowCount() > 0) {
            return ($all === false) ? $stmt->fetch() : $stmt->fetchAll();
        }
        return false;
    }

    public function hasColumns($field,$key,$value)
    {   $constraint = strtoupper($key);
        if($this->validKeys($constraint)){
        $fetch = $this->allColumns($field);     
        if($fetch->$constraint === $value)
        {
            return true;
        }
        else
        {
            return false;
        }
        }
        else
        {
            Schema::$migrationError[self::$table][] = "Selected Key is not valid in database";
            Schema::$migrationFailed[self::$table] = true;
        }
        return false;
       
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