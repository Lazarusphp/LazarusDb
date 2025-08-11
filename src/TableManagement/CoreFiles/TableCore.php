<?php
namespace LazarusPhp\LazarusDb\TableManagement\CoreFiles;

use LazarusPhp\LazarusDb\Database\CoreFiles\Database;

class TableCore extends Database
{
    protected string $table;
    protected  $tableName;
    protected  $column;

    public function __construct()
    {
        parent::__construct();
    }

   

}
