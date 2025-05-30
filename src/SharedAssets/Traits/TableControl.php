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

    public function allColumns()
    {
        $query = "SHOW COLUMNS FROM `" . Schema::getTable() . "`";
        // Use a direct PDO query to avoid interfering with self::$sql
        $stmt = $this->query($query);
        if ($stmt && $stmt->rowCount() > 0) {
            $column = [];
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $column[] = $row;
            }

        return $column;
        }
        return false;
    }

    public function hasColumns($value)
    {
        foreach($this->allColumns() as $column)
        {
            if(isset($column["Field"]) && $column["Field"] === $value)
            {
                return true;
            }
        }
        return false;
    }

}