<?php

namespace LazarusPhp\LazarusDb\SharedAssets\Traits;
use LazarusPhp\LazarusDb\SchemaBuilder\Schema;
use App\System\Classes\ErrorHandler\ErrorTest;
use Exception;


trait ArrayControl
{


    public static function keyExists($key, $array)
    {
        if (array_key_exists($key, $array)) {
            Schema::$migrationError[] = "Key '$key' exists in the array.";
            return true;
        } else {
            return false;
        }
    }

    public static function inArray($key, $array)
    {
        if (in_array($key, $array)) {
            return true;
        } else {
            return false;
        }
    }

    public static function isArray($array, $message)
    {
        if (!is_array($array)) {
            trigger_error($message);
            exit();
        }
    }

    public static function getType($value)
    {
        if (is_string($value)) {
            return 'string';
        } elseif (is_int($value)) {
            return 'int';
        } elseif (is_bool($value)) {
            return 'bool';
        } elseif (is_float($value)) {
            return 'float';
        } elseif (is_array($value)) {
            return 'array';
        } elseif (is_null($value)) {
            return 'null';
        } elseif (is_object($value)) {
            return 'object';
        } elseif (is_resource($value)) {
            return 'resource';
        } else {
            return 'unknown';
        }
    }

    public static function isString($string)
    {
      return is_string($string) ? true : false;
    }

    public static function isInt($int, $message)
    {
        if (!is_int($int)) {
            throw new \Exception($message);
            exit();
        }
    }

    public static function isBool($bool, $message)
    {
        if (!is_bool($bool)) {
            throw new \Exception($message);
            exit();
        }
    }

    public static function isFloat($float, $message)
    {
        if (!is_float($float)) {
            throw new \Exception($message);
            exit();
        }
    }

    public static function isNull($null, $message)
    {
        if (!is_null($null)) {
            throw new \Exception($message);
            exit();
        }
    }

    public static function isEmpty($array, $message)
    {
        if (empty($array)) {
        }
    }
}