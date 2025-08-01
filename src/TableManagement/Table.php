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
            unset($this->schemaCommands);
            unset($this->schemaAction);
            // unset(self::$query);

            self::$fk = [];
            self::$primaryKey = [];
            $this->schemaAction = [];
            $this->schemaCommands = [];
            self::$query = [];

    }


    public function build()
    {

        // Return as a string.
        return SchemaActions::processParams();
       
            // $this->processIndexes();
            // $this->loadFk();
        
            //  Functions::dd(self::$query);

            // implode the array to text with a trailing comma
            // Apply $this->modifier to each column at the beginning

            
        }
}
