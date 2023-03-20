<?php

namespace Mvc\Db;

class db
{

    private $connection;
    private $sql;

    public function __construct($data)
    {
        $this->connection = mysqli_connect($data[0], $data[1], $data[2], $data[3]);
    }

    //Select Function
    public function select($table, $column = "*")
    {
        $this->sql = "SELECT $column FROM `$table`";
        return $this;
    }

    //Where Function
    public function where($column, $operator, $value)
    {
        $this->sql .= " WHERE `$column` $operator '$value' ";
        return $this;
    }

    //Print All Rows 
    public function rows()
    {
        $query = mysqli_query($this->connection, $this->sql);
        if (is_object($query)) {
            return mysqli_fetch_all($query, MYSQLI_ASSOC);
        } else {
            return $this->show_error();
        }
    }

    public function first()
    {
        $query = mysqli_query($this->connection, $this->sql);
        if (is_object($query)) {
            return mysqli_fetch_assoc($query);
        } else {
            return $this->show_error();
        }
    }

    //Delete function 
    public function delete($table)
    {
        $this->sql = "DELETE FROM `$table`";
        return $this;
    }

    public function insert($table, $data)
    {
        $columns = '';
        $values = '';
        foreach ($data as $key => $value) {
            $columns .= "`$key`,";
            $values .= "'$value',";
        }
        $columns = rtrim($columns, ",");
        $values = rtrim($values, ",");
        $this->sql = "INSERT INTO `$table` ($columns)  VALUES ($values)";
        return $this;
    }

    public function edit($table, $data)
    {
        $row = '';
        foreach ($data as $column => $value) {
            $row .= "`$column` = '$value' ,";
        }
        $row = rtrim($row, ",");
        $this->sql = "UPDATE `$table`  SET $row";
        return $this;
    }

    public function search($table, $data)
    {
        $row = "";
        foreach ($data as $column => $value) {
            $row .= "`$column` LIKE '%$value%'";
        }
        $this->sql = "SELECT * FROM $table WHERE " . $row;
        return $this;
    }

    public function andWhere($column, $operator, $value)
    {
        $this->sql .= "AND `$column` $operator '$value'";
        return $this;
    }


    public function orWhere($column, $operator, $value)
    {
        $this->sql .= "OR `$column` $operator '$value'";
        return $this;
    }

    public function execute()
    {
        $query = mysqli_query($this->connection, $this->sql);
        if (is_object($query)) {
            return mysqli_affected_rows($this->connection);
        } else {
            return $this->show_error();
        }
    }

    public function join($type, $table, $primary, $foreign)
    {
        $this->sql .= "$type JOIN $table ON $primary = $foreign";
        return $this;
    }

    private function show_error()
    {
        return mysqli_error_list($this->connection);
    }

    public function __destruct()
    {
        mysqli_close($this->connection);
    }
}

// $o = new db(["localhost","root","","learning_management_system"]);
// // print_r($o);
// echo "<pre>";
// print_r($o->search('`categories`',["title"=>"M"])->rows());
?>