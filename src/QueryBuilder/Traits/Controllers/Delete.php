<?php
namespace LazarusPhp\LazarusDb\QueryBuilder\Traits\Controllers;

trait Delete
{
    
    public function delete()
    {
        $this->sql .= "DELETE FROM ". $this->table . " ";
        return $this;
    }
}