<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder;

use LazarusPhp\LazarusDb\SchemaBuilder\CoreFiles\SchemaCore;
use LazarusPhp\LazarusDb\TableManagement\TableControl;

class SchemaActions extends SchemaCore
{
    private static $params = [];
    private static $column;
    private static $method = [];

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
            return null;
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

            if (!isset(self::$params[self::$table][$name])) {
                self::$params[self::$table][$name] = [];
            }
        }
        // Merge with existing column params if they exist

        if (isset(self::$params[self::$table][$name][$action])) {
            self::$params[self::$table][$name] = array_merge(
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
        return null;
    }

    private static function passModifier($props)
    {
        if (isset($props["modifier"])) {
            return $props["modifier"]["command"];
        }
        return null;
    }

    private static function passNullable($props)
    {
        if (isset($props["nullable"])) {
            return $props["nullable"]["command"];
        }
        return null;
    }

    private static function passDefault($props)
    {
        if (isset($props["default"])) {
            return $props["default"]["command"];
        }
        return null;
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
        return null;
    }

    private static function passPrimary($props)
    {
        if (isset($props["primary"])) {
            return $props["primary"]["command"];
        }
        return null;
    }

    private static function passIndexes($props)
    {
        if (isset($props["indexes"])) {
            return $props["indexes"]["command"];
        }
        return null;
    }

    private static function passUniques($props)
    {
        if (isset($props["uniques"])) {
            return $props["uniques"]["command"];
        }
        return null;
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
            $attributes = self::passAttributes($props);
            $null = self::passNullable($props);
            $default = self::passDefault($props);
            $position = self::passPosition($props);
            $primary = self::passPrimary($props);
            $indexes = self::passIndexes($props);
            $uniques = self::passUniques($props);

            // Move this into its own Section.
            $columns[] = trim("$modifier $datatype $attributes $null $default $position");
        }

        if (count($columns) > 0) {
            $query["datatypes"] = $columns;
            return $query["datatypes"];
        } else {
            return [];
        }

        // Code for Database table goes here

        // Verify and match both local and database code to see if they match or dont match
    }

}
