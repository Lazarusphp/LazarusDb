<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder;

use LazarusPhp\LazarusDb\SchemaBuilder\Interfaces\SchemaLoaderInterface;
use Reflection;
use ReflectionClass;

class SchemaLoader
{
    public $table;
    public $classname;
    private SchemaLoaderInterface $schemaLoaderInterface;

    public static function load(string $dir)
    {
        $scandir = scandir($dir);
        foreach($scandir as $directory)
        {
            if($directory !== "." && $directory !== "..")
            {
                $filename = pathinfo($directory,PATHINFO_FILENAME);
                new SchemaLoader("Schemas\\$filename");
            }
        }
    }

    private function hasbody($schema,$methodname)
    {
          $reflection = new ReflectionClass($schema);
            if ($reflection->hasMethod($methodname)) {
                $method = $reflection->getMethod($methodname);
                // Check if the method is user-defined (not abstract or interface)
                if ($method->getFileName() !== false && $method->getStartLine() !== $method->getEndLine()) {
                   // Check if the method has a non-empty body
                    $filename = $method->getFileName();
                    $startLine = $method->getStartLine();
                    $endLine = $method->getEndLine();
                    $lines = file($filename);
                    $methodBody = implode("", array_slice($lines, $startLine, $endLine - $startLine - 1));
                    // Remove whitespace and braces
                    $methodBodyStripped = trim(str_replace(['{', '}'], '', $methodBody));
                    if ($methodBodyStripped === '') {
                        return false;
                    }
                    return true;
                    
                }
                else
                {
                    return false;
                }
            }
    }

    public function __construct($schema)
    {
        $this->table = strtolower($this->classname($schema)->getShortName());

        $this->schemaLoaderInterface = new $schema();
        if(class_exists($schema))
        {
       
            if(method_exists($this->schemaLoaderInterface,"drop") && $this->hasbody($this->schemaLoaderInterface,"drop"))
            {
                $this->schemaLoaderInterface->drop($this->table);
            }
                $this->schemaLoaderInterface->create($this->table);
   
        }
    }

    public function classname($classname)
    {
        return new ReflectionClass($classname);
    }

    public function loadSchema($schema)
    {
        $this->classname = $this->classname($schema)->getShortname();
        $this->table = strtolower($schema);
    
    }
}