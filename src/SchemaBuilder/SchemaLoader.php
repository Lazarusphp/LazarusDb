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
    private SchemaLoaderInterface $schemaLoaderInterface;



    public static function load(string $dir, string $method = "")
    {
        
        if (is_dir($dir) === false) {
            throw new \Exception("Directory not found");
        }

        $scandir = scandir($dir);
        foreach ($scandir as $directory) {
            if ($directory !== "." && $directory !== "..") {
                $filename = pathinfo($directory, PATHINFO_FILENAME);
                new self("Migrations\\Schemas\\$filename",$method);
            }
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
        $this->table = strtolower($this->classname($schema)->getShortName());

        $this->schemaLoaderInterface = new $schema();
        if (class_exists($schema)) {

            if (!empty($method) && $method === "alter") {
                if (method_exists($this->schemaLoaderInterface, "alter") && TableControl::table($this->table)->hasTable()) {

                    if ($this->hasbody($this->schemaLoaderInterface, "alter")) {
                        $this->schemaLoaderInterface->alter($this->table);
                    }
                }
            }


            if (!TableControl::table($this->table)->hasTable()) {
                if ($this->hasbody($this->schemaLoaderInterface, "up")) {
                    $this->schemaLoaderInterface->up($this->table);
                } else {
                    Schema::$migrationError[$this->table][] = "Failed to install Up method has not data";
                    Schema::$migrationFailed[$this->table] = true;
                }
            }


                 if (method_exists($this->schemaLoaderInterface, "alter") && TableControl::table($this->table)->hasTable()) {

                    if ($this->hasbody($this->schemaLoaderInterface, "alter")) {
                        $this->schemaLoaderInterface->alter($this->table);
                    }
                }

            if (Schema::MigrationFailed() && method_exists($this->schemaLoaderInterface, "down") && $this->hasbody($this->schemaLoaderInterface, "down")) {
                $this->schemaLoaderInterface->down($this->table);
            }
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
