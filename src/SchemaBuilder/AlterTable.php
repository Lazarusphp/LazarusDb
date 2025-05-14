<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder;

use LazarusPhp\LazarusDb\CoreFiles\SchemaCore;
use LazarusPhp\LazarusDb\SchemaBuilder\Traits\DataTypes;
use LazarusPhp\LazarusDb\SchemaBuilder\Traits\Fk;
use LazarusPhp\LazarusDb\SchemaBuilder\Traits\Indexes;

class AlterTable extends SchemaCore
{

    use Fk;
    use DataTypes;
    use Indexes;

    

    public function add()
    {
        $this->query .= " ADD ";
        return $this;
    }

    public function modify()
    {
        $this->query .= " MODIFY COLUMN ";
        return $this;
    }

    public function rename($name,$name2)
    {
        $this->query = "RENAME COLUMN $name TO $name2 ";
        return $this->store();
    }

    public function drop($name)
    {
        $this->query .= " DROP COLUMN ". $name;
        return $this->store();
    }

        public function store()
    {
        $this->data[] = $this->query;
        // Reset the query property;
        $this->query = "";
    }

    public function build()
    {
        $trim = ",";
        return implode(",",$this->data);
    }
}