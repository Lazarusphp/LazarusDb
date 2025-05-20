<?php
namespace LazarusPhp\LazarusDb\QueryBuilder;

use Exception;
use LazarusPhp\LazarusDb\Database\Connection;
use PDO;
use PDOException;
use LazarusPhp\LazarusDb\QueryBuilder\CoreFiles\QueryBuilderCore;

class QueryBuilder extends QueryBuilderCore
{

    /**
     * @method __construct
     * @param string $table
     * @description This method is used to initialize the QueryBuilder class.
     * It sets the table name for the query builder. If no table name is provided,
     * it generates a default table name based on the extended class name
     */
    public function __construct(string $table = "")
    {
        parent::__construct();
        empty($table) ? $this->generateTable() : $this->table = $table;
    }

 // get Table

 /**
  * @method table
    * @param string $table
    * @return QueryBuilder
    * @description This method is used to set the table name for the query builder.
    * It creates a new instance of the QueryBuilder class with the specified table name.
    * This allows you to chain methods for building SQL queries on the specified table.
    * @example QueryBuilder::table('users')->select()->where('id', 1)->get();
  */
    public static function table($table)
    {
        return new self($table);
    }

    /**
     * Clone the current QueryBuilder instance with a different database connection.
     * @param Connection $connection
     * @return static
     */

    // Pull and count data
    
    /**
     * @method get
     * @param int $fetch
     * @return array|false
     * @description This method is used to execute the SQL query and fetch the results.
     * It returns an array of results or false if no results are found. 
     */#
    public function get($fetch = \PDO::FETCH_OBJ)
    {
        $query = $this->store();

        if($query->rowCount() >= 1){
        return $query->fetchAll($fetch);
        }
        else
        {
            trigger_error("Users cannot be found");
        }
    }

    public function toJson($fetch = \PDO::FETCH_OBJ)
    {
        $query = $this->store();
        if($query->rowCount() >= 1){
            echo "Hello";
            return json_encode($query, JSON_PRETTY_PRINT);
          
        }
        elseif($query->rowCount() === 1)
        {
            $query = $query->first($fetch);
            return json_encode($query($fetch), JSON_PRETTY_PRINT);
        }
        else
        {
            trigger_error("Users cannot be found");
        }
    }


    /**
     * @method countRows
     * @return int|false
     * @description This method is used to count the number of rows returned by the query.
     * It returns the row count or false if no rows are found.
     */



    public function countRows()
    {
        $count =  $this->store()->rowCount();
        if($count === 0)
        {  
            return false;
        }
        else
        {
            return $count;
        }
    }


    // temporary method for supporing Sessions Class
    public function save()
    {
        return $this->store();
    }




    /**
     * @method store
     * @return string @property $this->toSql
     * @description Method returns string format of sql query including params
     */
    public function toSql()
    {
        // Escape special characters for safe output
        $this->processQuery();
        return htmlspecialchars($this->sql, ENT_QUOTES, 'UTF-8');
    }



    /**
     * @method first
     * @param int $fetch
     * @return object|false
     * @description method is used to return a single row based on the query.
     * 
     * object return if true else trigger an error message
     */
    public function first($fetch = \PDO::FETCH_OBJ)
    {
        $query = $this->store();
        if($query->rowCount() === 1)
        {
            return $query->fetch($fetch);
        }
        return false;
    }

    

    
}
