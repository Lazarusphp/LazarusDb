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
        $query = $this->store();
     
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
        $count = $this->store()->rowCount();
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
        $this->processQuery();
        return $this->sql;
    }

    public function first($fetch = \PDO::FETCH_OBJ)
    {
        $query = $this->store();
        if($query->rowCount() === 1)
        {
            return $query->fetch($fetch);
        }
        return false;
    }

    public function asJson()
    {
        $query = $this->store();
        $count = $query->rowCount();
        if($count == 1)
        {
           $json = $query->fetch();
        }
        if($count > 1)
        {
           $json = $query->fetchAll();
        }

        return json_encode($json,JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);

    }





    

    

    
}
