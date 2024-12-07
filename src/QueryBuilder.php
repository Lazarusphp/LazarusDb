<?php
namespace LazarusPhp\DatabaseManager;
use PDO;
use PDOException;

class QueryBuilder extends Database
{


    
    public function sql($sql,$params=null)
    {
        $this->sql = $sql;
        !is_null($params) ?  $this->param = $params : false;
        return $this;
    }


    public function asQuery(string $sql=null,  array $array = []):mixed
    {
        !is_null($sql) ? $this->sql = $sql : false;
        // Get the Params
        if (!empty($array)) $this->param = $array;
        // Check there is a connection
        try {
            $this->stmt = $this->connection->prepare($this->sql);
            if (!empty($this->param)) $this->bindParams();
            $this->param = [];
            $this->connection->beginTransaction();
            $this->stmt->execute();
            $this->connection->commit();
            return $this->stmt;
        } catch (PDOException $e) {
            $this->connection->rollback();
            throw $e;
        }
    }

     private function bindParams():void
    {
        if (!empty($this->param)) {
            // Prepare code
            foreach ($this->param as $key => $value) {
                $type = $this->getParamType($value);
                $this->stmt->bindValue($key, $value,$type);
            }
        }
    }

     private function getParamType($value):mixed
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
    public function one($type=PDO::FETCH_OBJ):mixed
    {
        $stmt =  $this->asQuery($this->sql,$this->params);
        return $stmt->fetch($type);
    }

    public function all($type=PDO::FETCH_OBJ)
    {
        $stmt = $this->asQuery();
        return $stmt->fetchAll($type);
    }

    public function rows():int
    {
        $stmt = $this->asQuery();
        return $stmt->rowCount();
    }
}
