<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder;

use App\System\Core\Functions;
use LazarusPhp\LazarusDb\SchemaBuilder\Interfaces\TableInterface;
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
use LazarusPhp\LazarusDb\TableManagement\TableControl;

class SchemaActions extends SchemaCore implements TableInterface
{
    private static $params = [];
    private static $column;
    private static $method = [];

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
            return self::$params[self::$table];
        } else {
            
        }
    }

    protected static function tableControl()
    {
        return new TableControl(self::$table);
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


    protected static function params(string $name, string $action, array $array): void
    {
        // Initialize the table if not already set
        if (!isset(self::$params[self::$table])) {
            self::$params[self::$table] = [];

        }

         if (!isset(self::$params[self::$table][$name])) {
                self::$params[self::$table][$name] = [];
            }
        
        if(!isset(self::$params[self::$table][$name][$action]))
        {
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
        if (isset($props["datatype"])) {
            return $props["datatype"]["command"];
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
                Schema::$migrationError[self::$table] = "Datatype " . $props["datatype"]["function"] . " is not allowed for attributes";
                Schema::$migrationFailed[self::$table] = true;
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
    $params = self::getParams();
    $references = [];

        foreach ($params as $table => $properties) {
        if (!isset($properties["indexes"])) {
            continue;
        }

        
        foreach ($properties["indexes"] as $ref => $data) {
            // Ensure this index reference exists
            if (!isset($references[$ref])) {
                $references[$ref] = [];
            }

            // Append the column name to this index reference
            $references[$ref][] = $data["name"];
        }
    }

    // Build final INDEX statements
    foreach ($references as $idxName => $columns) {
        // If duplicate index name exists, group all columns into one INDEX
        $columnList = implode(', ', array_unique($columns));
        self::$query["indexes"][] = "INDEX $idxName ($columnList)";
    }
}      

    private static function passUniques($props)
    {
      $params = self::getParams();
    $references = [];

        foreach ($params as $table => $properties) {
        if (!isset($properties["uniques"])) {
            continue;
        }

        
        foreach ($properties["uniques"] as $ref => $data) {
            // Ensure this index reference exists
            if (!isset($references[$ref])) {
                $references[$ref] = [];
            }

            // Append the column name to this index reference
            $references[$ref][] = $data["name"];
        }
    }

    // Build final INDEX statements
    foreach ($references as $idxName => $columns) {
        // If duplicate index name exists, group all columns into one INDEX
        $columnList = implode(', ', array_unique($columns));
        self::$query["uniques"][] = "CONSTRAINT UNIQUE $idxName ($columnList)";
    }
    }

    public static function passfk()
    {
        $fk = [];
        $commands = [];
        $params = self::getParams();

        foreach($params as $indexes => $properties)
        {
           
            if(isset($properties["fk"])){
                $props = $properties["fk"];
             if(!isset($fk[$indexes]))
            {
                $fk[] = $indexes;
            }

            if(isset($props["table"]) && isset($props["column"]))
            {
                $fk[$indexes] = [
                    "table"=>$props["table"],
                    "columns"=>$props["column"]
                ];
            }
            
            if(isset($properties["fkDelete"]))
                {
                $props = $properties["fkDelete"];
                
            if(isset($props["command"]))
            {
                $delete = $props["command"];
            }
            }            

            if(isset($properties["fkUpdate"])){
                $props = $properties["fkUpdate"];
                
            if(isset($props["command"]))
            {
                $update = $props["command"];
            }
            }   
             self::$query["fk"][] =  "FOREIGN KEY (".$properties["fk"]['currentColumn'].") REFERENCES ".$properties["fk"]['table']." (".$properties["fk"]['column'].") ON DELETE $delete ON UPDATE $update";
       
        }

        // End foreach loop below
    }
}

    protected static function processParams()
    {

        $table = self::tableControl();
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
            
            foreach(self::$query as $key => $value)
            {
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

            if(count($columns)){
            self::$query = [];
            return implode(", ", $columns);
            }


        // Code for Database table goes here

        // Verify and match both local and database code to see if they match or dont match
    }


    public function build()
    {

        // Return as a string.
        return self::processParams();
       
 
        }
}
