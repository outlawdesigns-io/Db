<?php

class Db{

  protected $_host;
  protected $_user;
  protected $_password;

  public $query;
  public $select;
  public $table;
  public $where;
  public $and;
  public $or;
  public $like;
  public $link;
  public $between;
  public $data = array();
  public $database;

  public function __construct(){
    $this->_loadEnvSettings();
    $this->query = "";
  }
    public function database($database){
      $this->database = $database;
      $this->query = '';
      return $this;
    }
    protected _loadEnvSettings(){
      if(!$this->_host = getenv('MYSQL_HOST')){
        throw new Exception('Unable to acces Environment variable: MYSQL_HOST');
      }
      if(!$this->_user = getenv('MYSQL_USER')){
        throw new Exception('Unable to acces Environment variable: MYSQL_USER');
      }
      if(!$this->_password = getenv('MYSQL_PASSWORD')){
        $this->_password = "";
        //don't throw an exception here in case password is actually empty
        //throw new Exception('Unable to acces Environment variable: MYSQL_PASSWORD');
      }
      return $this;
    }
    public function connect(){
      $this->link = mysqli_connect(HOST,USER,PASSWORD,$this->database);
      if (!$this->link) {
        $exceptionStr = "Connection Failed: " . mysqli_connect_error();
        throw new Exception($exceptionStr);
      }
      if(!mysqli_set_charset($this->link,"utf8mb4")){
        $exceptionStr = mysqli_error($this->link);
        throw new Exception($exceptionStr);
      }
      return true;
    }
    public function createDatabase($dbName){
      $this->query = "CREATE database " . $dbName;
      return $this;
    }
    public function createTable($tableArr){
      if(!isset($tableArr['name']) || !isset($tableArr['columns'])){
        throw new \Exception('Malformed Input Array');
      }
      $this->query = "create table " . $tableArr['name'] . "(\n";
      foreach($tableArr['columns'] as $col=>$options){
        $this->query .= $col . " ";
        for($i = 0; $i < count($options); $i++){
          if($i == (count($options) - 1)){
            $this->query .= $options[$i] . ",\n";
          }else{
            $this->query .= $options[$i] . " ";
          }
        }
      }
      $this->query .= "PRIMARY KEY (" . $tableArr['primaryKey'] . ")\n";
      $this->query .= ");";
      return $this;
    }
    public function drop($name,$table = true){
      if($table){
        $this->query = "drop table " . $name;
      }else{
        $this->query = "drop database " . $name;
      }
      return $this;
    }
    public function truncate(){
      $this->query = "truncate table " . $this->table;
      return $this;
    }
    public function select($select){
        $this->select = "SELECT " . $select . " FROM ";
        $this->query .= "SELECT " . $select . " FROM " . $this->table . "\n";
        return $this;
    }
    public function table($table){
        $this->table = "$table";
        return $this;
    }
    public function where($where,$conditional,$condition){
        $this->query .= " WHERE " . $where . " " . $conditional . " " . "" . $condition . "";
        return $this;
    }
    public function andWhere($where,$conditional,$condition){
        $this->query .= " AND " . $where . " " . $conditional . " " . $condition . "";
        return $this;
    }
    public function orWhere($where,$conditional,$condition){
        $this->query .= " OR " . $where . " " . $conditional . " " . $condition . "";
        return $this;
    }
    public function orderBy($condition){
        $this->query .= " ORDER BY " . $condition . "\n";
        return $this;
    }
    public function groupBy($condition){
      $this->query .= " GROUP BY " . $condition . "\n";
      return $this;
    }
    public function join($table,$condition1,$conditional,$condition2){
      $this->query .= " JOIN " . $table . " ON " . $condition1 . " " . $conditional . " " . $condition2 . "\n";
      return $this;
    }
    public function having($where,$conditional,$condition){
      $this->query .= " HAVING " . $where . " " . $conditional . " " . $condition . "";
      return $this;
    }
    public function delete(){
      $this->query .= "DELETE FROM  " . $this->table . "\n";
      return $this;
    }
    public function insert($data){
      $str = "INSERT INTO " . $this->table . " (";
      foreach($data as $key=>$value){
        $str .= "`" . $key . "`,";
      }
      $str .= ")";
      $str = preg_replace('/,([^,]*)$/', '\1', $str);
      $str .= " VALUES (";
      foreach($data as $key=>$value){
        $str .= "'" . $value . "',";
      }
      $str .= ")";
      $str = preg_replace('/,([^,]*)$/', '\1', $str);
      $this->query = $str;
      return $this;
    }
    public function update($data){
      $colCount = count($data);
      $i = 0;
      $str = "UPDATE " . $this->table . " SET ";
      foreach($data as $key=>$value){
        if(++$i == $colCount){
          $str .= $key . " = '" . $value . "'";
        }else{
          $str .= $key . " = '" . $value . "' ,";
        }
      }
      $this->query = $str;
      return $this;
    }
    public function put(){
      $this->connect();
      $sql = $this->query;
      if (!mysqli_query($this->link,$sql)){
        $exceptionStr = "Query Failed: " . mysqli_error($this->link);
        throw new Exception($exceptionStr);
      }
      return true;
    }
    public function get($structure = "object"){
      if(!$this->connect()){
        $exceptionStr = mysqli_error($this->link);
        throw new Exception($exceptionStr);
      }
      $sql = $this->query;
      $results = mysqli_query($this->link,$sql);
      if (!$results){
        throw new Exception(mysqli_error($this->link));
      }
      else{
        switch ($structure){
          case "object":
            return $results;
          break;
          default:
            return $results;
        }
      }
      //return $this;
    }
    public function uuid(){
      $data = '';
      $this->query = 'SELECT uuid() as uuid';
      $results = $this->get();
      while($row = mysqli_fetch_assoc($results)){
        $data = $row['uuid'];
      }
      return $data;
    }
}
