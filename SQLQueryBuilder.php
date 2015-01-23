<?php

/**
 * Created by Ramdhan.
 * User: Ramdhan
 * Date: 11/27/2014
 * Time: 10:00 PM
 */

class SQLQueryBuilder {

    private $_condition  = false;
    private $_where      = array();
    private $_join       = array();
    private $_group_by   = "";
    private $_order_by   = "";
    private $_limit      = "";

    /**
     * @param $table_name
     * @param string $columns
     * @return mixed|string
     */
    public function generateSelect($table_name, $columns = "*")
    {
        $str = "select {$columns}".
            " from {$table_name}{$this->_generateJoin()}{$this->_generateWhere()}
			{$this->_generateGroupBy()}{$this->_generateOrderBy()}{$this->_generateLimit()}";
        $str = preg_replace("/'noQuote\((.*)\)'/", "$1", $str);
        return $str;
    }

    /**
     * @param $table_name
     * @param string $column
     * @param null $alias
     * @return mixed|string
     */
    public function generateSelectCount($table_name, $column = "*", $alias = null)
    {
        $str = "select count({$column})".($alias != null? " as {$alias} " : "").
            " from {$table_name}{$this->_generateJoin()}{$this->_generateWhere()}
			{$this->_generateGroupBy()}{$this->_generateOrderBy()}{$this->_generateLimit()}";
        $str = preg_replace("/'noQuote\((.*)\)'/", "$1", $str);
        return $str;
    }

    /**
     * @param $table_name
     * @param $column
     * @param null $alias
     * @return mixed|string
     */
    public function generateSelectMax($table_name, $column, $alias = null)
    {
        $str = "select max({$column})".($alias != null? " as {$alias} " : "").
            " from {$table_name}{$this->_generateJoin()}{$this->_generateWhere()}";
        $str = preg_replace("/'noQuote\((.*)\)'/", "$1", $str);
        return $str;
    }

    /**
     * @param $table_name
     * @param $column
     * @param null $alias
     * @return mixed|string
     */
    public function generateSelectMin($table_name, $column, $alias = null)
    {
        $str = "select min({$column})".($alias != null? " as {$alias} " : "").
            " from {$table_name}{$this->_generateJoin()}{$this->_generateWhere()}";
        $str = preg_replace("/'noQuote\((.*)\)'/", "$1", $str);
        return $str;
    }

    /**
     * @param $table_name
     * @param $column
     * @param null $alias
     * @return mixed|string
     */
    public function generateSelectSum($table_name, $column, $alias = null)
    {
        $str = "select sum({$column})".($alias != null? " as {$alias} " : "").
            " from {$table_name}{$this->_generateJoin()}{$this->_generateWhere()}";
        $str = preg_replace("/'noQuote\((.*)\)'/", "$1", $str);
        return $str;
    }

    /**
     * @param $table_name
     * @param $column
     * @param null $alias
     * @return mixed|string
     */
    public function generateSelectAvg($table_name, $column, $alias = null)
    {
        $str = "select avg({$column})".($alias != null? " as {$alias} " : "").
            " from {$table_name}{$this->_generateJoin()}{$this->_generateWhere()}";
        $str = preg_replace("/'noQuote\((.*)\)'/", "$1", $str);
        return $str;
    }

    /**
     * @param $table_name
     * @param $data
     * @return mixed|string
     */
    public function generateInsert($table_name, $data)
    {
        $array_fields = array();
        $array_values = array();
        foreach($data as $key => $val)
        {
            $array_fields[] = $key;
            $array_values[] = is_numeric($val)? $val : "'{$val}'";
        }

        $fields = implode(", ", $array_fields);
        $values = implode(", ", $array_values);
        $str = "insert into {$table_name}({$fields}) values({$values})";
        $str = preg_replace("/'noQuote\((.*)\)'/", "$1", $str);
        return $str;
    }

    /**
     * @param $table_name
     * @param $data
     * @return mixed|string
     */
    public function generateUpdate($table_name, $data)
    {
        $array_fields_values = array();
        foreach($data as $key => $val)
        {
            $array_fields_values[] = $key . " = ".(is_numeric($val)? $val : "'{$val}'");
        }

        $fields_values = implode(", ", $array_fields_values);
        $str = "update {$table_name} set {$fields_values}{$this->_generateWhere()}";
        $str = preg_replace("/'noQuote\((.*)\)'/", "$1", $str);
        return $str;
    }

    /**
     * @param $table_name
     * @return mixed|string
     */
    public function generateDelete($table_name)
    {
        $str = "delete from {$table_name}{$this->_generateWhere()}";
        $str = preg_replace("/'noQuote\((.*)\)'/", "$1", $str);
        return $str;
    }

    /**
     * @param $table_name
     * @param $relation
     * @return $this
     */
    public function join($table_name, $relation)
    {
        $this->_join = array_merge($this->_join, array($table_name => $relation));
        return $this;
    }

    /**
     * @return mixed|string
     */
    private function _generateJoin()
    {
        $str = "";
        if(count($this->_join))
        {
            foreach($this->_join as $table_name => $relation)
            {
                $str .= " join {$table_name} on {$relation}";
            }
        }
        $str = preg_replace("/'noQuote\((.*)\)'/", "$1", $str);
        return $str;
    }

    /**
     * @param $key
     * @param $op
     * @param $val
     * @return $this
     */
    public function where($key, $op, $val)
    {
        $this->_condition = true;
        $this->_where[] = array($key => array($conj = "and", $op, $val));
        return $this;
    }

    /**
     * @param $key
     * @param $op
     * @param $val
     * @return $this
     * @throws Exception
     */
    public function orWhere($key, $op, $val)
    {
        if($this->_condition)
        {
            $this->_where[] = array($key => array($conj = "or", $op, $val));
        }
        else
        {
            throw new Exception("You cannot use ".
                (__class__)."->".(__function__)." at first condition.");
        }
        return $this;
    }

    /**
     * @return mixed|string
     */
    private function _generateWhere()
    {
        $str = "";
        if(count($this->_where))
        {
            $str = " where";
            for($i = 0; $i < count($this->_where); $i++)
            {
                foreach($this->_where[$i] as $key => $keySet)
                {
                    if(preg_match("/in/i", $keySet[1]) or is_numeric($keySet[2]))
                    {
                        $str .= ($i != 0 ? " {$keySet[0]} " : " ") . $key . " {$keySet[1]} {$keySet[2]}";
                    }
                    else
                    {
                        $str .= ($i != 0 ? " {$keySet[0]} " : " ") . $key . " {$keySet[1]} '{$keySet[2]}'";
                    }
                }
            }
        }
        $str = preg_replace("/'noQuote\((.*)\)'/", "$1", $str);
        return $str;
    }

    /**
     * @param $keys
     * @return $this
     */
    public function groupBy($keys)
    {
        $this->_group_by = " group by {$keys}";
        return $this;
    }

    /**
     * @return mixed
     */
    private function _generateGroupBy()
    {
        return $this->_group_by;
    }

    /**
     * @param $key
     * @param string $method
     * @return $this
     */
    public function orderBy($key, $method = "asc")
    {
        $this->_order_by = " order by {$key} {$method}";
        return $this;
    }

    /**
     * @return mixed
     */
    private function _generateOrderBy()
    {
        return $this->_order_by;
    }

    /**
     * @param $limit
     * @param int $offset
     * @return $this
     */
    public function limit($limit, $offset = 0)
    {
        $this->_limit = " limit {$offset}, {$limit}";
        return $this;
    }

    /**
     * @return mixed
     */
    private function _generateLimit()
    {
        return $this->_limit;
    }
}

$obj = new SQLQueryBuilder();
echo $obj->generateDelete("table_name");