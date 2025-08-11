<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder;

use LazarusPhp\LazarusDb\SchemaBuilder\Interfaces\SchemaLoaderInterface;
use LazarusPhp\LazarusDb\TableManagement\TableControl;
use Reflection;
use ReflectionClass;

class SchemaLoader
{
    public $table;
    public $classname;
    private static $targetname;
    private $validator;
    private SchemaLoaderInterface $schemaLoaderInterface;



    public static function load(string $dir, string $method, string  $target = "")
    {
        if (!is_dir($dir)) {
            throw new \Exception("Directory not found: $dir");
        }

        $files = scandir($dir);

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            // Only handle PHP files
            if (pathinfo($file, PATHINFO_EXTENSION) !== 'php') {
                continue;
            }

            $filename = pathinfo($file, PATHINFO_FILENAME);
            if (!$filename) {
                continue;
            }

            // If a target is provided, only process that one
            if ($target !== '' && strtolower($filename) !== strtolower($target)) {
                continue;
            }

            $class = "Migrations\\Schemas\\$filename";

            if (!class_exists($class)) {
                throw new \Exception("Class $class does not exist.");
            }

            new self($class, $method);
        }
    }

    private function hasbody($schema, $methodname)
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
            } else {
                return false;
            }
        }
    }

    public function __construct($schema, $method)
    {

        $classname = $this->classname($schema);
        $this->table = strtolower($classname->getShortName());
        $this->validator =    new SchemaValidator($this->table);
            $this->schemaLoaderInterface = new $schema();
            if (class_exists($schema)) {
                if ($method) {

                    if ($method === "up") {
                        $this->migrateTable($method);
                    }

                    if ($method === "alter") {
                        $this->modifyTable($method);
                    }

                    if ($method === "down") {
                        $this->dropTable($method);
                    }
                }
            } else {
                "Error Finding CLass";
            }
        
    }

    private function migrateTable($method)
    {
        if (!$this->validator->hasTable($this->table)) {
            if (method_exists($this->schemaLoaderInterface, $method)) {

                if ($this->hasbody($this->schemaLoaderInterface, $method)) {
                    $this->schemaLoaderInterface->up($this->table);
                } 
                else {
                    SchemaErrors::generate("Cannot create table $this->table", ["reason" => "$method code has not Body in it"]);
                }
            } else {
                SchemaErrors::generate("Cannot create table $this->table", ["reason" => "Method $method does not exist"]);
            }
        }
    }

    private function modifyTable($method)
    {
        
        if ($this->validator->hasTable($this->table)) {
            if (method_exists($this->schemaLoaderInterface, $method)) {

                if ($this->hasbody($this->schemaLoaderInterface, $method)) {
                    $this->schemaLoaderInterface->alter($this->table);
                } 
        
            } else {
                SchemaErrors::generate("Cannot Alter table $this->table", ["reason" => "Method $method does not exist"]);
            }
        } else {
            SchemaErrors::generate("Cannot Alter table $this->table", ["reason" => "Table $this->table is reqired"]);
        }
    }

    private function dropTable($method)
    {
        echo "We are doing it";
        if ($this->validator->hasTable($this->table)) {
            if (method_exists($this->schemaLoaderInterface, $method)) {

                if ($this->hasbody($this->schemaLoaderInterface, $method)) {
                    $this->schemaLoaderInterface->down($this->table);
                }

            } else {
                SchemaErrors::generate("Cannot drop table $this->table", ["reason" => "Method $method does not exist"]);
            }
        } else {
            SchemaErrors::generate("Cannot drop table $this->table", ["reason" => "Table $this->table is reqired"]);
        }
    }



    public function classname($classname)
    {
        return new ReflectionClass($classname);
    }
}
