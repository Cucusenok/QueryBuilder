<?php

interface QueryBuilder{
    public function createTable($table);
    public function insert($table, $args);
    public function select($table, $args);
    public function where();
}



class MySQLQueryBuilder implements QueryBuilder{

    private $con;
    private $query;

    public function __construct(PDO $pdoConnect){
        $this->con = $pdoConnect;
    }

    public function clear(){
        $this->query = "";
        return $this;
    }

    public function createTable($table){
        $this->query .= "CREATE TABLE $table ( ";
        return $this;
    }

    public function endCreateTable(){
        $this->query = substr($this->query, 0, -1);
        $this->query .= " );";
    }

    //sql types


    public function char($name, $size){
        $this->query .= " $name CHAR($size) ,";
        return $this;
    }

    public function varchar($name, $size){
        $this->query .= " $name VARCHAR($size) ,";
        return $this;
    }


    public function text($name){
        $this->query .= " $name TEXT,";
        return $this;
    }

    public function int($name){
        $this->query .= " $name INT,";
        return $this;
    }


    public function longtext($name){
        $this->query .= " $name LONGTEXT,";
        return $this;
    }
    
    
    //end sql types
    

    public function insert($table, $args){
        print("hello");
    }

    //function for setting necessary fields and tables
    public function select($tables, $args){
        if($args = 'all'){ // if need select all
            $this->query .= "SELECT * FROM " . implode(array($tables), ", ");  } 
        else{
             $this->query .= "SELECT " .implode($args, ", "). " FROM " . implode($tables, ", ");  }
        return $this;
    }

    //function for setting conditions in query
    //return  MySQLQueryBuilder object
    public function where(){
        $this->query .= " WHERE ";
        return $this;
    }

    //comparison operators
    public function like($field, $arg){
        $this->query .= " $field LIKE '%$arg%' ";
        return $this;
    }

    public function equal($field, $arg){
        $this->query .= " $field = $arg ";
        return $this;
    }
    //end comparison operators


    //logical operators
    public function and(){
        $this->query .= " AND ";
        return $this;
    }

    public function or(){
        $this->query .= " OR ";
        return $this;
    }
    
    public function not(){
        $this->query .= " NOT ";
        return $this;
    }

    //end logical operators


    public function truncateTable($table){
        $this->query .= "TRUNCATE TABLE $table";
        return $this;
    }
    

     //work with alter table
    public function SetPrimaryKey($table, $arg){
        $this->query .= " ALTER TABLE $table
                          ADD PRIMARY KEY ($arg) ";
        return $this;
    }

    public function DropPrimaryKey($table){
        $this->query .= "ALTER TABLE $table
                         DROP PRIMARY KEY";
    }
     //end work with alter table


    //finction for getting all area in DB
    //return Array
    public function getAll(){
        return $this->con->query($this->query)->fetchAll();
    } 

    //funtion for getting current query
    // return String
    public function get_query(){
        return $this->query;
    }

    //func for making query if query return nothing
    public function makeQuery(){
        $this->con->query($this->query);
        return $this;
    }
}



class ConnectDB{
    public static function create($host, $dbname, $user, $pass){
        try{  return new PDO("mysql:host=$host; dbname=$dbname", $user, $pass);  }
        catch(Exception $e) { print("Exception" . $e->getMessage() . "\n"); die(); }
    }

}





$db =  new MySQLQueryBuilder(ConnectDB::create("localhost", "test", "root", ""));

$sel = $db->select('users', 'all')
          ->where()
          ->not()->like("name", "fi"); //Example for geting fields with conditions

$users_array = $sel->getAll(); //getting fata field in array

//var_dump($sel); 


$sel->clear(); //clear query

$sel->createTable("Cars")
    ->int("id")
    ->varchar("name", 255)
    ->varchar("model", 255)
    ->endCreateTable()
    ->SetPrimaryKey("Cars", "id")
    ->makeQuery();


print('---------------<br>');

var_dump($sel->get_query());


//var_dump($sel);
//print($sel->get_query());
