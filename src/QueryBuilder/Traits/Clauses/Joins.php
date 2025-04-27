<?php

namespace LazarusPhp\LazarusDb\QueryBuilder\Traits\Clauses;

trait Joins
{
    protected  $joins  = [];
    // Renamed innerjoin to join for consistency
    public  function join(string $table,string $key, string|int $value, ?string $alias=null)
    {

        $this->joins[] = " INNER JOIN $table ";

        if(!is_null($alias))
        {
         $this->joins[] = " $alias ";
        }

        $this->on($key,$value);

        return $this;
    }

    public  function rightJoin(string $table,string $key, string|int $value, ?string $alias=null)
    {

        $this->joins[] = " RIGHT JOIN $table ";

        if(!is_null($alias))
        {
         $this->joins[] = " $alias ";
        }

        $this->on($key,$value);

        return $this;
    }

    public  function crossJoin(string $table,string $key, string|int $value, ?string $alias=null)
    {

        $this->joins[] = " CROSS JOIN $table ";

        if(!is_null($alias))
        {
         $this->joins[] = " $alias ";
        }

        $this->on($key,$value);

        return $this;
    }

    public  function leftJoin(string $table,string $key, string|int $value, ?string $alias=null)
    {

        $this->joins[] = " LEFT JOIN $table ";

        if(!is_null($alias))
        {
         $this->joins[] = " $alias ";
        }

        $this->on($key,$value);

        return $this;
    }

    public  function fullJoin(string $table,string $key, string|int $value, ?string $alias=null)
    {

        $this->joins[] = " FULL JOIN $table ";

        if(!is_null($alias))
        {
         $this->joins[] = " $alias ";
        }

        $this->on($key,$value);

        return $this;
    }


    private  function  on($key,$value)
    {
        $condition = "$key=$value";
        $this->joins[] = "ON $condition";
    }

    public  function fetchJoins()
    {
        $joins = [];
        if(count($this->joins)) {
            foreach ($this->joins as $join) {
                $joins[] = $join;
            }
            $this->sql .= implode(" ", $joins);
        }
    }
}