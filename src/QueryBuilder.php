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


    public function validateFilters()
    {
        if (count($this->allowed) > 0) {
            $allowed = array_diff($this->allowed, $this->filtered);
            return implode(", ", $allowed);
        } else {
            return "*";
        }
    }

    public function save()
    {
        return $this->store();
    }

    public function toSql()
    {
        // Escape special characters for safe output
        $this->processQuery();
        return htmlspecialchars($this->sql, ENT_QUOTES, 'UTF-8');
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


    public function saveUpdate()
    {
        $sql = $this->sql;
        echo $this->sql;
    }



    

    

    
}
