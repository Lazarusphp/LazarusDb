<?php
namespace LazarusPhp\DatabaseManager;
use PDO;
use PDOException;

class QueryBuilder extends Database
{

    // Insert new values
    public function create()
    {

    }


    // Select / Read Values
    public function read()
    {

    }

    // updated Values
    public function update()
    {

    }

    // Delete Selected values
    public function delete()
    {
        $sql = "Select * from users";
        $this->save($sql);
    }

    
}
