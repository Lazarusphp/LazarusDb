<?php

namespace LazarusPhp\DatabaseManager\CoreFiles;
use LazarusPhp\DatabaseManager\QueryBuilder\Traits\Controllers\Insert;
use LazarusPhp\DatabaseManager\QueryBuilder\Traits\Controllers\Delete;
use LazarusPhp\DatabaseManager\QueryBuilder\Traits\Controllers\Update;
use LazarusPhp\DatabaseManager\QueryBuilder\Traits\Controllers\Select;
use LazarusPhp\DatabaseManager\QueryBuilder\Traits\Clauses\Grouping;
use LazarusPhp\DatabaseManager\QueryBuilder\Traits\Clauses\Having;
use LazarusPhp\DatabaseManager\QueryBuilder\Traits\Clauses\Joins;
use LazarusPhp\DatabaseManager\QueryBuilder\Traits\Clauses\Limit;
use LazarusPhp\DatabaseManager\QueryBuilder\Traits\Clauses\Order;
use LazarusPhp\DatabaseManager\QueryBuilder\Traits\Clauses\Where;
use ReflectionClass;

use PDO;


abstract class QueryBuilderCore extends Database
{

    // Set Properties
    protected $sql;
    protected $table;
    protected $param = [];
    // End Properties

    // Load Trait Files;
    use Insert;
    use Select;
    use Update;
    use Delete;
    // Load condition Traits
    use Where;
    use Joins;
    use Limit;
    use Order;
    use Grouping;
    use Having;


    public function __construct()
    {
        // Instantiate a Blank $sql statement
        $this->sql = "";

        // Ensure the parent Database class initializes the connection
        parent::__construct();
    }

    // Table Management
    protected function generateTable()
    {
        $class = get_called_class();
        $reflection  = new ReflectionClass($class);
        return $this->table = strtolower($reflection->getShortName());
    }

   
    // End Table Management.

    // Magic Setters and Getters


    public function __set($name, $value)
    {
        $this->param[$name] = $value;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->param)) {
            return $this->param[$name];
        }
    }

    // End Magic Setters and Getters


    // Param Binding

    protected function bindParams(): void
    {
        if (!empty($this->param)) {
            // Prepare code
            foreach ($this->param as $key => $value) {
                $type = $this->getParamType($value);
                $this->stmt->bindValue($key, $value, $type);
            }
        }
    }

    // Get the Param Type
    protected function getParamType($value)
    {
        switch ($value) {
            case is_bool($value):
                return PDO::PARAM_BOOL;
            case is_null($value):
                return PDO::PARAM_NULL;
            case is_int($value):
                return PDO::PARAM_INT;
            case is_string($value):
                return PDO::PARAM_STR;
            default;
                break;
        }
    }

    // End Param Binding


    // Store or request data from database
    public function store(): mixed
    {
        try {
            $this->stmt = $this->prepare($this->sql);
            if (!empty($this->param)) $this->bindParams();
            $this->beginTransaction();
            $this->stmt->execute();
            $this->commit();
            return $this->stmt;
        } catch (PDOException $e) {
            $this->rollback();
            throw $e->getMessage();
        }
    }
}
