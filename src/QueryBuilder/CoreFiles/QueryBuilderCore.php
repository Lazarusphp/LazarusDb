<?php

namespace LazarusPhp\LazarusDb\QueryBuilder\CoreFiles;

use LazarusPhp\LazarusDb\Database\CoreFiles\Database;
use LazarusPhp\LazarusDb\QueryBuilder\Traits\Controllers\Insert;
use LazarusPhp\LazarusDb\QueryBuilder\Traits\Controllers\Delete;
use LazarusPhp\LazarusDb\QueryBuilder\Traits\Controllers\Update;
use LazarusPhp\LazarusDb\QueryBuilder\Traits\Controllers\Select;
use LazarusPhp\LazarusDb\QueryBuilder\Traits\Clauses\Grouping;
use LazarusPhp\LazarusDb\QueryBuilder\Traits\Clauses\Having;
use LazarusPhp\LazarusDb\QueryBuilder\Traits\Clauses\Joins;
use LazarusPhp\LazarusDb\QueryBuilder\Traits\Clauses\Limit;
use LazarusPhp\LazarusDb\QueryBuilder\Traits\Clauses\Order;
use LazarusPhp\LazarusDb\QueryBuilder\Traits\Clauses\Where;
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


    

    // New ProcessQUery Will Store the Passed Values.
    protected  function processQuery()
    {
        $this->fetchJoins();
        $this->fetchWhere();
        $this->fetchGroupBy();
        $this->fetchHaving();
        $this->fetchOrderBy();
        $this->fetchLimit();
    }

    // Magic Setters and Getters


    /**
     * @mehtod __set
     * @param string $name , $name of the array
     * @param mixed $value , value of array can be int for string
     * @description : method is used to store values into an array using magic methods.
     */
    public function __set($name, $value)
    {
        // Check if no array exists with the same name.
        if(!array_key_exists($name,$this->param)){
            // Store the value to the array with the $name parameter.
        $this->param[$name] = $value;
        }else{
            // Throw an exception stating the error doesnt exist
            throw new \Exception("The parameter $name already exists in the query builder.");
        }
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->param)) {
            if(is_scalar($this->param[$name]) && !is_null($this->param[$name]))
            {
                return $this->param[$name];
            }
            else
            {
                throw new \Exception("The parameter $name is an invalud type or is null");
            }
        }
        else {
            throw new \Exception("The parameter $name does not exist in the query builder.");
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

    private function unbind()
    {
        $this->param = [];
    }

    // End Param Binding






    // Store or request data from database
    public function store(): mixed
    {

        // Process Query 
        $this->processQuery();
        try {
            $this->stmt = $this->prepare($this->sql);
            if (!empty($this->param)) $this->bindParams();
            $this->beginTransaction();
            $this->stmt->execute();
            $this->commit();
            $this->unbind();
            return $this->stmt;
        } catch (PDOException $e) {
            $this->rollback();
            throw $e->getMessage();
        }
    }
}
