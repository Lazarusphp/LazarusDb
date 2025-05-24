<?php

namespace LazarusPhp\LazarusDb\SharedAssets\Traits;
use LazarusPhp\LazarusDb\SchemaBuilder\Schema; 
use Exception;


trait ArrayControl
{

    public $error = [];


    public function keyExists($key,$array,$message)
    {
        if(array_key_exists($key,$array))
        {
            Schema::$progress = false;
            $this->error[] = $message;
            return true;
        }
        else{
         Schema::$progress = true;
        }
    }

    public function loadErrors()
    {
            return $this->error;

    }

    public function inArray($key,$array,$message)
    {
        if(!in_array($key,$array))
        {
            trigger_error($message);
            Schema::$progress = false;
            $this->error[] = $message;
            return true;
        }
        else{
               schema::$progress = true;
                return false;
        }
 
    }  

    public function isArray($array,$message)
    {
        if(!is_array($array))
        {    
            trigger_error($message);
            exit();
        }
    }   

    public function isString($string,$message)
    {
        if(!is_string($string))
        {
            throw new \Exception($message);
            exit();
        }
    }

    public function isInt($int,$message)
    {
        if(!is_int($int))
        {
            throw new \Exception($message);
            exit();
        }
    }

    public function isBool($bool,$message)
    {
        if(!is_bool($bool))
        {
            throw new \Exception($message);
            exit();
        }
    }

    public function isFloat($float,$message)
    {
        if(!is_float($float))
        {
            throw new \Exception($message);
            exit();
        }
    }   

    public function isNull($null,$message)
    {
        if(!is_null($null))
        {
            throw new \Exception($message);
            exit();
        }
    }
    
    public function isEmpty($array,$message)
    {
        if(empty($array))
        {
            throw new \Exception($message);
            exit();
        }
    }
}