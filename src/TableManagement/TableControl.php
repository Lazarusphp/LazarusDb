<?php

namespace LazarusPhp\LazarusDb\TableManagement;

use LazarusPhp\LazarusDb\Database\CoreFiles\Database;
use LazarusPhp\LazarusDb\TableManagement\CoreFiles\TableCore;
use LazarusPhp\LazarusDb\SchemaBuilder\Schema;

class TableControl extends TableCore
{
    public static function table($table)
    {
        
        return new self($table);
    }

    public function __construct(string $table="")
    {
        $this->table = (Schema::getTable() && empty($table)) ? Schema::getTable() : $table;
        parent::__construct();
    }


    public function hasTable()
    {
          $query = "SELECT * ";
                $query .= " FROM INFORMATION_SCHEMA.COLUMNS ";
                $query .= " WHERE TABLE_SCHEMA='" . $_ENV['dbname'] . "' ";
                $query .= " AND TABLE_NAME = '" . $this->table . "'";
        $result = $this->query($query);
        if ($result && $result->rowCount() >= 1) {
            $this->tableName = $result->fetch();
            return true;
      
        } else {
            return false;
        }
    }

        public function hasTablebyColumn($column)
    {
        $query = "SELECT * ";
        $query .= " FROM INFORMATION_SCHEMA.COLUMNS ";
        $query .= " WHERE TABLE_SCHEMA='" . $_ENV['dbname'] . "' ";
        $query .= " AND TABLE_NAME = '" . $this->table . "'";
        $query .= " AND COLUMN_NAME = '" . $column . "'";
        $result = $this->query($query);
        if ($result && $result->rowCount() === 1) {
            $this->tableName = $result->fetch();
            return true;
        } else {
            return false;
        }
    }
    
    // Call any Field
    public function field(string $name,string $field)
    {
        // Check if any character in $field is lowercase
        if (preg_match('/[a-z]/', $field))
        {
            $field = strtoupper($field);
        }
        if($this->hasTablebyColumn($name))
        {
            switch($field)
            {
                case "DATA_TYPE":
                    return $this->tableName->DATA_TYPE;
                case "MAXCHARS":
                    return $this->tableName->CHARACTER_MAXIMUM_LENGTH;
                case "IS_NULLABLE":
                    return $this->tableName->IS_NULLABLE;
                case "COLUMN_NAME":
                    return $this->tableName->COLUMN_NAME;
                case "COLUMN_DEFAULT":
                    return $this->tableName->COLUMN_DEFAULT;
                default:
                    echo "Field $field does not exist";
            }
        }
        return false;
        
    }

    public function isNullable(bool $bool=false)
    {
        $bool = $bool === true ? "YES" : "NO";
        if($this->hasTable())
        {
            return $this->tableName->IS_NULLABLE === "YES" ? true : false;
        }

    }

    public function validField($column,$value)
    {
    

        if($this->hasTable())
        {
       if (preg_match('/[a-z]/', $column))
        {
            $column = strtoupper($column);
        }

        if($this->validKeys($column))
        {
            if($this->tableName->$column === $value)
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
            echo "Columns $column is not valid";
        }
        }
    }
    public function isPrimary($value)
    {
        if ($this->hasTable()) {
            $query = "SELECT * FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA='" . $_ENV['dbname'] . "' AND TABLE_NAME='" . $this->table . "' AND CONSTRAINT_NAME='PRIMARY'";
            $stmt = $this->query($query);
            if ($stmt && $stmt->rowCount()) {
                $result = $stmt->fetch();
                return $result->COLUMN_NAME === $value ? true : false;
            }
            return false;
        }
    }
}
