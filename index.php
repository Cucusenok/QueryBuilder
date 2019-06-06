<?php

interface QueryBuilder{
    public function create($table, $args);
    public function insert($table, $args);
    public function select($table, $args);
    public function where($argc);
}



class MySQLQueryBuilder implements QueryBuilder{

    private $con;
    private $query;

    public function __construct(PDO $pdoConnect){
        $this->con = $pdoConnect;
    }

    public function create($table, $args){
        print("hello");
    }
    public function insert($table, $args){
        print("hello");
    }

    //function for setting necessary fields and tables
    public function select($tables, $args){
        if($args = 'all'){
            $this->query .= "SELECT * FROM " . implode($tables, ", ");  } 
        else{
             $this->query .= "SELECT " .implode($args, ", "). " FROM " . implode($tables, ", ");  }
        
        return $this;
        //return $this->con->query("SELECT * FROM $table");
    }

    //function for setting conditions in query
    //return  MySQLQueryBuilder object
    public function where($args){
        $this->query .= " WHERE " . implode(" AND ", $args);
        return $this;
    }


    private function convert_query(&$query){
        str_replace(array("==", "is"), "=", $query);
    }

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
}



class ConnectDB{
    public static function create($host, $dbname, $user, $pass){
        try{  return new PDO("mysql:host=$host; dbname=$dbname", $user, $pass);  }
        catch(Exception $e) { print("Exception" . $e->getMessage() . "\n"); die(); }
    }

}



$db =  new MySQLQueryBuilder(ConnectDB::create("localhost", "test", "root", ""));

$sel = $db->select('users', 'all')
          ->where(array("id=0", "id=1"))
          ->get_query();

print($sel);



//var_dump($sel);
//print($sel->get_query());
