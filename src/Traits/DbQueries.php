<?php
namespace LazarusPhp\DatabaseManager\Traits;
use PDO;
use PDOException;

trait DbQueries
{


    public function GenerateQuery($sql=null, $array = [])
    {
        !is_null($sql) ? $this->sql = $sql : false;
        // Get the Params
        if (!empty($array)) $this->param = $array;

        // Check there is a connection
        try {
            $this->stmt = $this->connection->prepare($this->sql);
            if (!empty($this->param)) $this->BindParams();
            $this->param = [];
            $this->stmt->execute();
            return $this->stmt;
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage() . $e->getCode());
        }
    }

    final public function BindParams()
    {
        if (!empty($this->param)) {
            // Prepare code
            foreach ($this->param as $key => $value) {
                $type = $this->GetParamTpe($value);
                $this->stmt->bindValue($key, $value,$type);
            }
        }
    }

    private function GetParamTpe($value)
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


    public function AddParams($name, $value)
    {
        $this->param[$name] = $value;
        return $this;
    }

/**
 * Undocumented function
 *
 * @param [type] $type
 * @return void
 * Use Sql Function to chainload the following functions 
 * Updated 18/06/2024
 */
    public function One($type = PDO::FETCH_OBJ)
    {
        $stmt = $this->GenerateQuery();
        return $stmt->fetch($type);
    }

    public function All($type = PDO::FETCH_OBJ)
    {
        $stmt = $this->GenerateQuery();
        return $stmt->fetchAll($type);
    }

    public function FetchColumn()
    {
        $stmt = $this->GenerateQuery();
        return $stmt->fetchColumn();
    }

    public function RowCount()
    {
        $stmt = $this->GenerateQuery();
        return $stmt->rowCount();
    }
}