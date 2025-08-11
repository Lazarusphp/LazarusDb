<?php
namespace LazarusPhp\LazarusDb\SchemaBuilder;

class SchemaErrors
{

    private static  $path;
    private static $asFile = false;
    private static $schemaErrors = [];

    public static function instantiate(string $path="")
    {
        if(!empty($path)){
            self::$path = $path;
            self::$asFile = $asFile = true;
        }
    }

  

    public static function generate(string $message,array $requirements)
    {
        $error_key = uniqid();
        $error_key = chunk_split($error_key,4,"-");
        $error_key = rtrim($error_key,"-");

        $data = ["message"=>$message];
        self::$schemaErrors["ErrorID : ".$error_key . " : "]  = (!empty($requirements)) ? array_merge($data,$requirements) : self::$schemaErrors[$error_key] = $data;
   }

    public static function returnErrors()
    {
        return json_encode(self::$schemaErrors);
        
    }

    public static function LoadErrors()
    {
                foreach(json_decode(self::returnErrors()) as $key => $errors)
                {
                echo "<h2>$key</h2>";
                foreach ($errors as $property => $value) {
                echo "<br>" . ucfirst($property) . ": " . htmlspecialchars((string) $value);
                }
                echo "<hr>";
            

                }
            }

    public static function countErrors()
    {
        if(count(self::$schemaErrors) >= 1)
        {
            return true;
        }
        return false;
    }
}