<?php
namespace Lazarus\LazarusDb;

use DbControl;

class Credentials implements DbControl
{
    private $hostname
    private $username;
    private $password;
    private $dbname;
    private $type;
    public $key;

    

    public function __construct()
    {
        // Check and Load the config file
    }
}