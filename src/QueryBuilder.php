<?php
namespace LazarusPhp\DatabaseManager;

use Exception;
use PDO;
use PDOException;

class QueryBuilder extends Database
{

    // Set Current Table Name
    public $table;
    public $where = [];
    // Set Current Modifier
    private $currentModifier;
    // Supported Modifiers
    private $supportedModifiers = ["read", "update", "delete"];
    // Set save Status
    public $saveStatus = false;
    // Set the Sql path;
    public $sql;

    public function __construct(?string $table = null)
    {
        // Call the parent constructor to get the connection
        parent::__construct();
        // table can now also be set in the constructor
        $table ? $this->table = $table : false;
    }

    public function read(callable $rawsql,array $selectvals=[])
    {
        // Obtain the current function by the method name.
        $this->currentModifier = __FUNCTION__;
        
        if(empty($selectvals))
        {
            $selectvals = "*";
        }
        else
        {
            $selectvals = implode(",",$selectvals);
        }
        $this->sql = "SELECT $selectvals FROM  $this->table ";

        if(is_callable($rawsql))
        {
            $rawsql($this,$selectvals);
        }
        return $this->saveRaw($this->sql);
    }

    // Insert new values
    public function create(array $values = [])
    {

        // Add Support for both array and object based values
        if (count($values) === 0) {
            
            $keys = implode(',', array_keys($this->param));
            $placeholders = ':' . implode(', :', array_keys($this->param));
        } else {
            // If Array is passed
            $keys = implode(',', array_keys($values));
            $placeholders = ':' . implode(', :', array_keys($values));
            $this->param = $values;
        }
        
        // set sql query
        $this->sql = "INSERT INTO " . $this->table;
        $this->sql .= " ($keys) ";
        $this->sql .= "VALUES($placeholders)";
        // Save and submit to the database.
        return $this->save($this->sql,$this->param);

        
 
    }
    // updated Values
    public function update(callable $rawsql)
    {
        $this->currentModifier = __FUNCTION__;
        $this->sql = "UPDATE " . $this->table . " SET ";
        foreach ($this->param as $key => $value) {

            $this->sql .= "$key='$value' ";
        }
        
        if(is_callable($rawsql))
        {
            $rawsql($this);
        }
        $this->sql = rtrim($this->sql, ",");
        echo $this->sql;
        return $this->saveRaw($this->sql);
  
    }

   public function where(string $key,int|string $value,?string $operator=null)
   {
        $operator = $operator ?? "=";
        $params = uniqid("where_");
         $condition = $key.$operator.":$params";
          if(count($this->where))
          {
              $condition = " AND " . $condition;
          }
          $this->where[] = $condition;
          $this->param[$params] = $value;
          return $this;
   }

    // Currently only supports 
    public function delete(callable $rawsql)
    {
        $this->currentModifier = __FUNCTION__;
        $this->sql = "DELETE FROM $this->table ";

        if(is_callable($rawsql))
        {
            $rawsql($this);
        }
        return $this->saveRaw($this->sql);
    }   

    /**
     *  Using addittional sql query
     *
     * @param [type] $sql
     * @return void
     */
   
    
        /**
     * Saves a raw sql query without param binding.
     *
     * @param [type] $sql
     * @return void
     */
    public function saveRaw($sql)
    {
        try {
            $this->stmt = $this->getConnection()->prepare($sql);
            if ($this->stmt->execute()) {
                $this->saveStatus = true;
                return $this->stmt;
            } else {
                $this->saveStatus = false;
                throw new Exception("SQL execution failed: " . implode(", ", $this->stmt->errorInfo()));
            }
        } catch (PDOException $e) {
            $this->saveStatus = false;
            throw new Exception($e->getMessage());
        }
    }

    public function save(?string $sql = null, $array = []): mixed
    { 
        !is_null($sql) ? $this->sql = $sql : false;
        // Get the Params
        if (!empty($array)) $this->param = $array;
        // Check there is a connection
        try {
            $this->stmt = $this->prepare($this->sql);
            if (!empty($this->param)) $this->bindParams();
            $this->beginTransaction();
            $this->stmt->execute();
            $this->lastId = $this->lastId();
            $this->commit();
            return $this->stmt;
        } catch (PDOException $e) {
            $this->rollback();
            throw $e->getMessage();
        }
    }


    
}
