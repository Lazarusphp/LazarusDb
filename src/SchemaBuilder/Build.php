<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder;

use LazarusPhp\LazarusDb\Interfaces\SchemaInterface;
use LazarusPhp\LazarusDb\CoreFiles\SchemaCore;
use LazarusPhp\LazarusDb\SchemaBuilder\Traits\DataTypes;
use LazarusPhp\LazarusDb\SchemaBuilder\Traits\Fk;
use LazarusPhp\LazarusDb\SchemaBuilder\Traits\Indexes;

class Build extends SchemaCore implements SchemaInterface
{

    // Added for future Use
    protected $build = [];
    private $methods;
    use Fk;
    use DataTypes;
    use Indexes;

    /**
     * Add Modify drop
     *
     * @param string $name
     * @return void
     */
    private function obtainMethod(string $name)
    {
        // Convert to lower.
        $name = strtolower($name);
        switch($name)
        {
            case "add":
                $this->query .= " ADD ";
                break;
            case "modify":
                $this->query .= " MODIFY ";
                break;
            case "drop":
                $this->query .= " DROP ";
                break;
        }
        return $this;
    }

    

    public function defaultDate()
    {
        $this->query .= " DEFAULT (CURRENT_DATE) ";
        return $this;
    }

    public function defaultDateTime()
    {
        $this->query .= " DEFAULT (CURRENT_TIMESTAMP) ";
        return $this;
    }

    public function default(string|int $value)
    {
        if (is_string($value)) {
            // Escape single quotes for SQL and wrap in single quotes
            $escaped = str_replace("'", "''", $value);
            $this->query .= " DEFAULT '$escaped' ";
        } else {
            $this->query .= " DEFAULT $value ";
        }
        return $this;
    }

    public function store()
    {
        $this->data[] = $this->query;
        // Reset the query property;
        $this->query = "";
    }

    


    public function unsigned()
    {
        $this->query .= " UNSIGNED ";
        return $this;
    }

    public function nullable($bool=true)
    {
        ($bool===false) ? $this->query .= " NOT NULL " : $this->query .= " NULL ";
        return $this;
    }


 

    // Constraint


    // On delete

    public function query()
    {
        return $this->query;
    }


    public function build()
    {
        $trim = ",";
        return implode(",",$this->data);
    }


}