<?php

namespace LazarusPhp\LazarusDb\TableManagement;

use App\System\Core\Functions;
use LazarusPhp\LazarusDb\SchemaBuilder\CoreFiles\SchemaCore;
use LazarusPhp\LazarusDb\SchemaBuilder\Interfaces\TableInterface;
use LazarusPhp\LazarusDb\SchemaBuilder\Schema;
use LazarusPhp\LazarusDb\SchemaBuilder\SchemaActions;
use LazarusPhp\LazarusDb\SchemaBuilder\Traits\After;
use LazarusPhp\LazarusDb\SchemaBuilder\Traits\Datatypes;
use LazarusPhp\LazarusDb\SchemaBuilder\Traits\Indexes;
use LazarusPhp\LazarusDb\SchemaBuilder\Traits\Nullable;
use LazarusPhp\LazarusDb\SchemaBuilder\Traits\Defaults;
use LazarusPhp\LazarusDb\SchemaBuilder\Traits\Attributes;
use LazarusPhp\LazarusDb\SchemaBuilder\Traits\Fk;
use LazarusPhp\LazarusDb\SchemaBuilder\Traits\Modifier;
use LazarusPhp\LazarusDb\SchemaBuilder\Traits\Position;

// use LazarusPhp\LazarusDb\SharedAssets\Traits\ArrayControl;

class Table extends SchemaActions implements TableInterface
{
    use Datatypes;
    use Indexes;
    use Fk;
    use Defaults;
    use Nullable;
    use Attributes;
    use Modifier;
    use Position;
    // use ArrayControl;

    public bool $buildFailed = false;

    private $schemaAction = [];
    private $schemaCommands = [];

    protected function getTable()
    {
        return self::$table;
    }

    // End Rebuild Commenting
  
    


    // Potentially Look at moving this
    
    private function unsetData():void
    {
            unset(self::$fk[self::$table]);
            unset(self::$primaryKey[self::$table]);
            unset(self::$action[self::$table]);
            unset($this->schemaCommands);
            unset($this->schemaAction);
            // unset(self::$query);

            self::$fk = [];
            self::$primaryKey = [];
            self::$action = [];
            $this->schemaAction = [];
            $this->schemaCommands = [];
            self::$query = [];

    }


    public function build()
    {

        // Return as a string.
        $columns = SchemaActions::processParams();
        self::$query["datatypes"] = $columns;
       
            // $this->processIndexes();
            $this->loadFk();

             Functions::dd(self::$query);

             exit();
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

            // implode the array to text with a trailing comma
            // Apply $this->modifier to each column at the beginning

            if(count($columns)){
            $this->unsetData();
            return implode(", ", $columns);
            }
        }
}
