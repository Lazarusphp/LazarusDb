<?php

namespace LazarusPhp\LazarusDb\QueryBuilder\Traits\Clauses;

trait Joins
{
    
    protected  $joins  = [];
    // Renamed innerjoin to join for consistency

     /**
     * @method join
     * @param string $table : table name to join
     * @param string $key : key is the name of the table/alias, this is passed to @method on 
     * @return $this
     */
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


     /**
     * @method rightJoin
     * @param string $table : table name to join
     * @param string $key : key is the name of the table/alias, this is passed to @method on 
     * @return $this
     */
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

     /**
     * @method crossJoin
     * @param string $table : table name to join
     * @param string $key : key is the name of the table/alias, this is passed to @method on 
     * @return $this
     */
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

    /**
     * @method leftJoin
     * @param string $table : table name to join
     * @param string $key : key is the name of the table/alias, this is passed to @method on 
     * @return $this
     */
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

    /**
     *  @method fullJoin
     *  @param string $table : table name to join
     *  @param string $key : key is the name of the table/alias, this is passed to @method on
     *  @param string|int $value : value of the join clause
     *  @param string|null $alias : alias for the table
     */
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


    /**
     *  @method on
     *  @param string $key : key is the name of the table/alias
     *  @param string|int $value : value of the join clause
     *  @description : This method is used to set the join condition for the query builder.
     *  @access private
     *  @return void
     */
    private  function  on($key,$value)
    {
        $condition = "$key=$value";
        $this->joins[] = "ON $condition";
    }

    /**
     * *  @method fetchJoins
     * *  @return array $joins : returns the joins array
     * *  @description : This method fetches the joins from the query builder and appends them to the SQL string.
     * *  @access protected
     */
    protected  function fetchJoins()
    {
        $joins = [];
        if(count($this->joins)) {
            foreach ($this->joins as $join) {
                $joins[] = $join;
            }
            
            $this->sql .= implode(" ", $this->joins);
        }
        return $joins;
    }

    // Predefined Joins

    /**
     * @method hasOne
     * @param string $table, Child table name.
     * @param string $child_key, Child table key.
     * @param string $parent_key, Parent table key.
     * @description : This method is used to join the child table with the parent table using the hasOne relationship.
     * order of parameters is important. child table required to join child table coloum, and parent column.
     * @access public
     * @return $this->join
     * @throws \Exception
     * @example $this->hasOne('users','id','user_id',1) =
     *  $this->join('users','users.id','user_id')->where("users.id,1)->first();
     */
    public function hasOne($table,$child_key,$parent_key,$id)
    {
        try{
            return $this->join($table,$table.".$child_key",$this->table.".$parent_key")->where($this->table.".$parent_key",$id)->first();
        }
        catch(\Exception $e){
            throw new \Exception("The table $table does not exist in the database.");
        }
    }

    public function belongsTo($table,$child_key,$parent_key,$id)
    {
        try{
            return $this->rightJoin($table,$table.".$child_key",$this->table.".$parent_key")->where($this->table.".$parent_key",$id,">=");
        }
        catch(\Exception $e){
            throw new \Exception("The table $table does not exist in the database.");
        }
    }

    
}