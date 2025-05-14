<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder\Traits;

trait Fk
{
        public function constraint()
    {
        $this->query .= "CONSTRAINT ";
        return $this;
    }
    
    // Foreign Key

    public function foreignKey($column)
    {
        $this->query .= " FOREIGN KEY fk_". self::$table. "($column) ";
        return $this;
    }
    //FK Reference

    public function references(string $table,string $column)
    {
        $this->query .= " REFERENCES $table($column)";
        return $this;
    }
    // use cascade no action restrict and set null.
    //  on update
    public function onUpdate(string $action="restrict")
    {
        $action = strtoupper($action);
        $this->query .= " ON UPDATE $action ";
        return $this;
    }

    public function onDelete(string $action="restrict")
    {
          $action = strtoupper($action);
        $this->query .= " ON DELETE $action ";
        return $this;
    }

}