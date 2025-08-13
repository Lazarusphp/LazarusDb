<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder;

use App\System\Core\Functions;
use LazarusPhp\LazarusDb\Database\CoreFiles\Database;
use LazarusPhp\LazarusDb\SchemaBuilder\CoreFiles\SchemaCore;
use PDO;

class SchemaValidator extends SchemaCore
{

    // Generate a Tablename

    protected $rows;
    private $column;
    protected $errors = [];


    /**
     * Table
     * @desciption static method Creates a new  table and column based on the parameters and passes to constructor
     * @method table;
     * @property string $table
     * @property string $column;
     * @requires @method string self::table;
     * @return void
     */


    /**
     * Constructor for class
     *
     * @param string $table
     * @param string $column // can be empty
     */
    public function __construct(string $table, $column = "")
    {
        self::$table = $table;
        $this->column = (!empty($column)) ? $column : "";
        // Requires parent contructor for methods such as self::$table
        parent::__construct();
    }

    public function getData()
    {
        Functions::dd($this->rows);
        
    }

    /**
     * has Table
     * Check if table exists
     * @param string $table
     * @param string $column
     * @requires __constructor($table,$column) or @method  table($table,$column)
     * @return boolean
     */
    public function hasTable()
    {
        $table = self::$table;
        if ($this->column) {
            $column = $this->column;
        }

        $query = "SELECT * ";
        $query .= " FROM INFORMATION_SCHEMA.COLUMNS";
        $query .= " WHERE TABLE_SCHEMA='" . $_ENV['dbname'] . "' ";
        $query .= " AND TABLE_NAME = '" . $table . "'";
        
        if (!empty($column) && is_string($column)) {
            $query .= " AND COLUMN_NAME = '" . $column . "'";
        }

        $result = $this->query($query);
        if ($result && $result->rowCount() >= 1) {
            $this->rows = $result->fetchALL();
            return true;
        } else {
            return false;
        }
    }

    /**
     * hasColumn method
     *
     * @param [string] $name
     * @return boolean
     */
    public function hasIndexes(string $indexName="",string $indexValue = "")
    {
        $table = self::$table;
        $column = $this->column;
        $indexName = $indexName;
        $indexValue = $indexValue;
    $query = "SELECT INDEX_NAME, COLUMN_NAME,NON_UNIQUE ";
    $query .= " FROM INFORMATION_SCHEMA.STATISTICS ";#
    $query .= " WHERE TABLE_SCHEMA = '".$_ENV["dbname"]."'";
    $query .= " AND TABLE_NAME = '$table' ";
    if(!empty($indexName))
    {
    $query .= " AND INDEX_NAME = '$indexName'";
    }
    
    if(!empty($indexValue) && !empty($indexName)){
    $query .= " AND COLUMN_NAME = '$indexValue'";
    }
    $result = $this->query($query);
    return $result->fetchAll();
    }

    public function hasForeignKey($column)
    {
        $query = "SELECT TABLE_SCHEMA, TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME";
        $query .= " FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE";
        $query .= " WHERE REFERENCED_TABLE_SCHEMA IS NOT NULL";
        $stmt = $this->query($query);
        if(!empty($column))
        {
                $query .= " AND COLUMN_NAME='{$column}'";
               $fetch =  $stmt->fetch();
        }
        else
        {
            $fetch = $stmt->fetchAll();
        }

        return $fetch;
    }

    public function hasColumn($name)
    {
        foreach ($this->rows as $col) {
            if ($col->COLUMN_NAME === $name) {
                $this->column = $name;
                return true;
                // break;
            }
        }
    }

    public function getField(string $field)
    {
        if ($this->column) {
            // Check if any character in $field is lowercase
            if (preg_match('/[a-z]/', $field)) {
                $field = strtoupper($field);
            }

            if($this->column)
            {
                echo $this->column;
            }
            
        }
    }

    /**
     * validFields
     *
     * @param [string] $field
     * @param string $value
     * @param string $value
     * @requires @method  hasColumn or @property string $column to be set
     * @return void
     */
    public function validField(string $field, string $value = "")
    {
        $value = (!empty($value) && !$this->column) ? $value : $this->column;
        // Detect if the text is lowercase
        if (preg_match('/[a-z]/', $field)) {
            $field = strtoupper($field);
        }

        // Check if the property Exists
        foreach ($this->rows as $data) {
            if (property_exists($data, $field) && $data->$field === $value) {
                return true;
            }
        }
        return false;
    }


    /**
     * @method  isnullable()
     * @requires @property string $this->column
     * @description Detects if a column is nullable
     */
    public function isNullable()
    {
        foreach($this->rows as $col)
        {
            if($this->column)
            {
                if($this->column === $col->COLUMN_NAME)
                {
                    if($col->IS_NULLABLE === "YES")
                    {
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                }
            }
            else
            {
                return false;
            }
        }
    }

    /**
     * @method  isPrimary()
     * @requires @property string $this->column
     * @description Detects if a column is set as primary
     */
    public function isPrimary()
    {
        foreach($this->rows as $col)
        {
            if($this->column)
            {
                if($this->column === $col->COLUMN_NAME)
                {
                    if($col->COLUMN_KEY === "PRI")
                    {
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                }
            }
            else
            {
                return false;
            }
        }
    }

    public function isDefault()
    {
        foreach($this->rows as $col)
        {
            if($this->column)
            {
                if($this->column === $col->COLUMN_NAME)
                {
                    if($col->COLUMN_DEFAULT === "NULL")
                    {
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                }
            }
            else
            {
                return false;
            }
        }
    }
}
