<?php
namespace LazarusPhp\LazarusDb;

use Exception;
use PDO;
use PDOException;
use LazarusPhp\LazarusDb\CoreFiles\QueryBuilderCore;

class QueryBuilder extends QueryBuilderCore
{

    public function __construct(string $table = "")
    {
        parent::__construct();
        empty($table) ? $this->generateTable() : $this->table = $table;
    }

 // get Table

    public static function table($table)
    {
        return new self($table);
    }

    public function raw(string $sql,array $params)
    {
        $this->sql = $sql;
        $this->param = $params;
        return $this;
    }

    // Pull and count data

    
    public function get($fetch = \PDO::FETCH_OBJ)
    {
        $query = $this->save();
        if($query->rowCount() >= 1){
        return $query->fetchAll($fetch);
        }
        else
        {
            trigger_error("Users cannot be found");
        }
    }

    public function countRows()
    {
        $count = $this->save()->rowCount();
        if($count === 0)
        {  
            return false;
        }
        else
        {
            return $count;
        }
    }


    public function validateFilters()
    {
        if (count($this->allowed) > 0) {
            $allowed = array_diff($this->allowed, $this->filtered);
            return implode(", ", $allowed);
        } else {
            return "*";
        }
    }

    public function toSql()
    {
        echo $this->sql;
    }

    public function first($fetch = \PDO::FETCH_OBJ)
    {
        $query = $this->save();
        echo "Helo";
        if ($query->rowCount() === 1) {
            $this->result = $query->fetch($fetch); // Store the result for chaining
            return $this;
        }
        $this->result = false; // Store false if no result
        return $this;
    }

    public function getResult()
    {
        return $this->result;
    }
    {
        $query = $this->save();
        if($query->rowCount() === 1)
        {
            return $query->fetch($fetch);
        }
        return false;
    }

    public function asJson()
    {
        $query = $this->save();
        $count = $query->rowCount();
        if($count == 1)
        {
           $json = $query->fetch();
        }
        if($count > 1)
        {
           $json = $query->fetchAll();
        }

        header("content-type:application/json");
        return json_encode($json,JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);

    }

    public function save(?string $sql = null, $array = [])
    {

        !is_null($sql) ? $this->sql = $sql : false;
        // Get the Params
        if (!empty($array)) $this->param = $array;

        $this->fetchJoins();
        $this->fetchWhere();
        $this->fetchGroupBy();
        $this->fetchHaving();
        $this->fetchOrderBy();
        $this->fetchLimit();
        return $this->store();
    }
    

    

    
}
