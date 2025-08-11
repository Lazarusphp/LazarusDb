<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder;

use App\System\Core\Functions;
use LazarusPhp\LazarusDb\SchemaBuilder\Schema;
use LazarusPhp\LazarusDb\SchemaBuilder\Traits\Datatypes;
use LazarusPhp\LazarusDb\SchemaBuilder\Traits\Indexes;
use LazarusPhp\LazarusDb\SchemaBuilder\Traits\Nullable;
use LazarusPhp\LazarusDb\SchemaBuilder\Traits\Defaults;
use LazarusPhp\LazarusDb\SchemaBuilder\Traits\Attributes;
use LazarusPhp\LazarusDb\SchemaBuilder\Traits\Fk;
use LazarusPhp\LazarusDb\SchemaBuilder\Traits\Modifier;
use LazarusPhp\LazarusDb\SchemaBuilder\Traits\Position;
use LazarusPhp\LazarusDb\SchemaBuilder\CoreFiles\SchemaCore;
use LazarusPhp\LazarusDb\SchemaBuilder\Interfaces\SchemActionInterface;
use LazarusPhp\LazarusDb\SchemaBuilder\SchemaErrors;
use LazarusPhp\LazarusDb\SchemaBuilder\SchemaValidator;

class SchemaActions extends SchemaCore implements SchemActionInterface
{
    private static $params = [];
    private static $column;
    private static $method = [];
    protected static $validator;

    use Datatypes;
    use Indexes;
    use Fk;
    use Defaults;
    use Nullable;
    use Attributes;
    use Modifier;
    use Position;

    public bool $buildFailed = false;

    public function __construct() {}

    protected static function column()
    {
        return self::$column;
    }

    public static function getParams(...$args)
    {
   
        if (isset(self::$params[self::$table])) {
            if(count($args) === 0){
            return self::$params[self::$table];
            }
            else
            {
                 $table = array_merge(self::$params[self::$table],$args);
                 return $table;
            } 
        }
        else {
        }
    }


    protected static function validator()
    {
        return self::$validator = new SchemaValidator(self::$table);
    }



    /**
     * @method method() 
     * @param string $method
     * @static
     *  method is designed to set a method using a  passed parameter and then ouptut when no param is passed
     * */
    public static function method(string $method = "")
    {
        if (empty($method)) {
            if (isset(self::$method[self::$table])) {
                return self::$method[self::$table];
            }
        } else {
            if (!isset(self::$method)) {
                self::$method[] = self::$table;
            }
            self::$method[self::$table] = $method;
        }
    }


    protected function processRequest($name, $action, $array)
    {
        $this->name = $name;
        SchemaActions::params($this->name, $action, $array);
    }
    

    protected static function unsetParams($name)
    {
        if(isset(self::$params[self::$table][$name]))
        {
            unset(self::$params[self::$table][$name]);
        }
        return self::$params[self::$table];
    }


    protected static function params(string $name, string $action, array $array): void
    {
        // Initialize the table if not already set
        if (!isset(self::$params[self::$table])) {
            self::$params[self::$table] = [];
        }

        if (!isset(self::$params[self::$table][$name])) {
            self::$params[self::$table][$name] = [];
        }

        if (!isset(self::$params[self::$table][$name][$action])) {
            self::$params[self::$table][$name][$action] = [];
        }
        // Merge with existing column params if they exist

        if (isset(self::$params[self::$table][$name][$action])) {
            self::$params[self::$table][$name][$action] = array_merge(
                self::$params[self::$table][$name][$action],
                $array
            );
        } else {
            self::$params[self::$table][$name][$action] = $array;
        }
    }

    private static function passDatatype($props)
    {
        $datatype = isset($props["datatype"]) ? $props["datatype"] : null;
        if($datatype)
        {
            return $datatype["command"];
        }
    }

    private static function passAi($props)
    {
        if (isset($props["ai"])) {
            return $props["ai"]["command"];
        }
    }

    private static function passModifier($props)
    {
        if (isset($props["modifier"])) {
            return $props["modifier"]["command"];
        }
    }

    private static function passNullable($props)
    {
        if (isset($props["nullable"])) {
            return $props["nullable"]["command"];
        }
    }

    private static function passDefault($props)
    {
        if (isset($props["default"])) {
            return $props["default"]["command"];
        }
    }

    private static function passAttributes($props)
    {
        if (isset($props["attributes"])) {
            $required = $props["attributes"]["requiredDatatype"];
            $exploded = explode("|", $required);

            if (!in_array($props["datatype"]["function"], $exploded)) {
                SchemaErrors::generate("Cannot Pass Attributes",["Reason"=>"Invalid Datatype used",
                "Supported Types"=>$exploded]);
                return false;
            } else {
                return $props["attributes"]["command"];
            }
        } else {
            return "";
        }
    }

    private static function passPosition($props)
    {
        if (isset($props["position"])) {
            return $props["position"]["command"];
        }
    }

    private static function passPrimary($props)
    {
        if (isset($props["primary"])) {
            self::$query["primary"] = $props["primary"]["command"];
        }
    }

    private static function passIndexes()
    {
        
        $validator = new SchemaValidator(self::$table);
        $params = self::getParams();
        $references = [];
        $commands = [];

        foreach ($params as $table => $properties) {
            if (!isset($properties["indexes"])) {
                continue;
            }


            foreach ($properties["indexes"] as $ref => $data) {
                $references[$ref][] = $data["name"];
                $commands[$ref] = $data["command"];
            }
        }

        // Build final INDEX stateme
        foreach ($commands as $idxName => $command) {
                $indexColumns = [];
                
                foreach($validator->hasIndexes($idxName) as $column)
                {
                    $indexColumns[] = $column->COLUMN_NAME;
                }
                
                if(count($indexColumns) >= 1)
                {
                    self::$query["indexes"][] = " DROP INDEX $idxName";
                }
                
                $command = (self::method() === "alter") ? "ADD $command" : $command;
                $columnList = implode(', ', array_unique($references[$idxName]));
                $queries = " $command $idxName ($columnList)";
                
         
                self::$query["indexes"][] = $queries;
             
                // echo $queries;
        
        }


    }

    private static function passUniques($props)
    {
        $validator = new SchemaValidator(self::$table);
        $params = self::getParams();
        $references = [];
        $commands = [];

        foreach ($params as $table => $properties) {
            if (!isset($properties["uniques"])) {
                continue;
            }


            foreach ($properties["uniques"] as $ref => $data) {
                $references[$ref][] = $data["name"];
                $commands[$ref] = $data["command"];
            }
        }

        // Build final INDEX stateme
        foreach ($commands as $idxName => $command) {
                $indexColumns = [];
                foreach($validator->hasIndexes($idxName) as $column)
                {
                    if($column->NON_UNIQUE === 0){
                        $indexColumns[] = $column->COLUMN_NAME;
                    }
                    else
                    {
                    echo $column->COLUMN_NAME . "is not unique";
                    }
                }
                
                if(count($indexColumns) >= 1)
                {
                    self::$query["indexes"][] = " DROP INDEX $idxName";
                }
                
                $command = (self::method() === "alter") ? "ADD $command" : $command;
                $columnList = implode(', ', array_unique($references[$idxName]));
                $queries = " $command $idxName ($columnList)";
                
         
                self::$query["uniques"][] = $queries;
             
                // echo $queries;
        
        }


    }

    public static function passfk()
    {


        $fk = [];
        $foreignKey = [];
        $params = self::getParams();
        $keys = [];

        // Generate date in loop
        foreach ($params as $indexes => $properties) {

            // Check if fk propery is valid
            if (isset($properties["fk"])) {
                $props = $properties["fk"];

                $table = $properties["fk"]["table"];
                $column = $properties["fk"]["column"];

                if (!isset($fk[$indexes])) {
                    $fk[] = $indexes;
                }

                if (isset($props["table"]) && isset($props["column"])) {
                    $fk[$indexes] = [
                        "table" => $props["table"],
                        "columns" => $props["column"]
                    ];
                }

                if (!array_key_exists($table, $keys)) {
                    $keys[$table] = [
                        "column" => $column,
                    ];
                }

                if (!in_array($table, $foreignKey)) {
                    $foreignKey[$table] = [];
                }


                if (isset($properties["fkDelete"])) {
                    $props = $properties["fkDelete"];

                    if (isset($props["command"])) {
                        $delete = $props["command"];
                    }
                }

                if (isset($properties["fkUpdate"])) {
                    $props = $properties["fkUpdate"];

                    if (isset($props["command"])) {
                        $update = $props["command"];
                    }
                }

                // Store Foreign key Command in an array for later
                $foreignKey[$table] = "FOREIGN KEY (" . $properties["fk"]['currentColumn'] . ") REFERENCES " . $properties["fk"]['table'] . " (" . $properties["fk"]['column'] . ") ON DELETE $delete ON UPDATE $update";
            }

            // End foreach loop below
        }

        // New Loop validate code against database or generate errors
        foreach ($keys as $key => $value) {
            if ($key) {
                $validator = new SchemaValidator($key);

                if (!$validator->hasTable()) {
                    SchemaErrors::generate("Foreign Key Cannot be Applied Because ", [
                        "table" => self::$table,
                        "reason" => "Table $key does not match Schema Migration Request"
                    ]);
                }

                if ($validator->hasTable()) {
                    if ($validator->hasColumn($value["column"]) === false) {
                        SchemaErrors::generate(
                            "Foreign Key Cannot be Applied",
                            [
                                "table" => self::$table,
                                "reason" => "Column does not match Schema Migration Request",
                                "required column" => $value["column"],
                            ]
                        );
                    }
                }
            } else {
            }
        }

        // Final execute foreign key array and add to self::$query
        foreach ($foreignKey as $table => $value) {
            self::$query["fk"][] = $value;
        }
    }



    protected static function processParams()
    {
        // Code for processing params goes here

        // dd(self::getParams());
        $columns = [];
        $params = self::getParams();
        if (!is_array($params)) {
            $params = [];
        }

        foreach ($params as $name => $props) {
            // Continue the script
            
            $datatype = self::passDatatype($props);
            $modifier = self::passModifier($props);
            $ai = self::passAi($props);
            $attributes = self::passAttributes($props);
            $null = self::passNullable($props);
            $default = self::passDefault($props);
            $position = self::passPosition($props);
            self::passPrimary($props);
            $columns[] = trim("$modifier $datatype $attributes $null $default $ai $position");
        
        }

        self::passIndexes();
        self::passUniques($props);
        self::passfk();

        self::$query["datatypes"] = $columns;

        $columns = [];

        foreach (self::$query as $key => $value) {
            // Check if Load Primary key and indexes are in an array
            if (is_array($value)) {
                foreach ($value as $item) {
                    $columns[] =   $item;
                }
                // output data as normal;
            } else {
                $columns[] = $value;
            }
        }



        if ((count($columns) && SchemaErrors::countErrors() === false)) {
            self::$query = [];
            return implode(", ", $columns);
        } else {
            echo " there are errors";
        }



        // Code for Database table goes here

        // Verify and match both local and database code to see if they match or dont match
    }


    public function build()
    {
        return self::processParams();
    }
}
