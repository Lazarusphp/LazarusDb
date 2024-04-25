<?php
namespace Lazarus\LazarusDb;

use function SnorkelWeb\DBManager\OpenConnection;

class SqlManager
{
    private $sql;
    public $stmt;
    public $connection;


    public function __construct($connection=null)
    {
        $db = new Database();
        $this->connection = $db->OpenConnection();
    }


    public function GenerateQuery(string $sql)
    {
        $this->
        $this->sql = $sql;
        $this->stmt = $this->connection->prepare();
        $this->stmt->execute();
        return $this;
    }

    public function BindValues($array)
    {
        // !is_null($array) ? $this->param = $array :  $this->param = array_combine($this->paramkey, $this->paramvalue);
        if (!empty($array)) {       // Prepare code
            foreach ($array as $key => $value) {
                //    Execute the loop and bind the parameters
                 $this->stmt->bindValue($key, $value);
            }
        }
    }


    //QUery Managemenent


    /**
     * Count Affected Rows for the database.
     *
     * @return void
     */

    public function RowCount()
    {
        return $this->stmt->rowCount();
    }



    // Fetch one result
    public function Fetch($type = null)
    {
        return $this->stmt->fetch($type);
    }

    // Fetch ALl Result
    public function Fetch_All($type = null)
    {
        return $this->stmt->fetchAll($type);
    }

    public function GetLastId()
    {
        return $this->connection->lastInsertId();
    }
}
