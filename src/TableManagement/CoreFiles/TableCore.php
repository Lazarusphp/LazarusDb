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

    // Valid Column Keys 
    protected function validKeys($key)
    {
        $validKeys = ["COLUMN_NAME","TABLE_NAME","TABLE_SCHEMA","COLUMN_KEY","DATA_TYPE","IS_NULLABLE","COLUMN_DEFAULT","COLUMN_DEFAULT"];
        return in_array($key,$validKeys) ? true : false;
    }

}
