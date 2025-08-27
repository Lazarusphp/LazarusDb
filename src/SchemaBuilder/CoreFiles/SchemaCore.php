<?php
namespace LazarusPhp\LazarusDb\SchemaBuilder\CoreFiles;

use Exception;
use LazarusPhp\LazarusDb\Database\CoreFiles\Database;
use LazarusPhp\LazarusDb\SchemaBuilder\Schema;
use LazarusPhp\LazarusDb\SchemaBuilder\SchemaErrors;
use LazarusPhp\LazarusDb\TableManagement\TableControl;
use PDO;
use PDOException;

abstract class SchemaCore extends Database
{

    protected $data = [];
    protected $errors = [];
    // Possibly Move Name to Ta
    protected $name;
    protected static $query = [];
    protected static $table;
    protected static $param = [];
    protected $isSelected = false;
    protected static $allowdBinding = [];
    // Sql Statement
    protected static $sql = "";

      protected function bindParams(): void
    {
        if (!empty(self::$param)) {
            // Prepare code
            foreach (self::$param as $key => $value) {
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

    // Unbind
    private function unbind()
    {
        self::$param = [];
    }


    // Save functions.
    protected function save(string $sql = "")
    {
       $sql = !empty($sql) ? $sql : self::$sql; 
        try {
            $this->stmt = $this->prepare($sql);
            if (!empty(self::$param) && count(self::$param)) $this->bindParams();
            if($this->stmt->execute())
            {
            if($this->isSelected === true)
            {
                $this->stmt->closeCursor();
            }
            $this->unbind();
        }
            return $this->stmt;
        } catch (PDOException $e) {
            SchemaErrors::generate("Cannot Write Schema", ["reason"=>$e->getMessage()]);
        }
   }

}