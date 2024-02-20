<?php
namespace core\DB;
require_once (getcwd(). '/core/loader.php');

class Db {
    private $connection;
    private static $db ;

    public static function getInstance($option = null){
        if(self::$db == null){
            self::$db = new Db($option);
        }
        return self::$db ;
    }
    public function __construct($option = null){
        if($option != null){
          $host = $option['host'] ;
          $user = $option['user'] ;
          $pass = $option['pass'] ;
          $name = $option['name'] ;
        }else{
            $host =  $_ENV['DB_HOST'];
            $user =  $_ENV['DB_USER'];
            $pass =  $_ENV['DB_PASSWORD'];
            $name =  $_ENV['DB_NAME'];
        }
        $this->connection = mysqli_connect($host,$user,$pass,$name);
        if($this->connection->connect_error){
            echo "connection Failed :" . $this->connection->connect_error ;
            exit;
        }
        $this->connection->query("SET NAMES 'utf8'");
    }
    public function first($sql){
        $records = $this->query($sql);
        if($records == null){
            return null ;
        }
        return $records[0];
    }

    public function insert($sql){
        $id = $this->connection->query($sql);
        return $id ;
    }
    public function modify($sql){
        $rowsEfected = $this->connection->query($sql);
        return $rowsEfected ;
    }
    public function update ($update){
       // $rowdate = echo "this Awnser =" ;
    }
    public function query($sql){
        $result = $this->connection->query($sql);
        $records = array();

        if($result->num_rows == 0){
            return null ;
        }

        while($row = $result->fetch_assoc()){
            $records[] = $row;
        }
        return $records ;
    }
    public function connection(){
        return $this->connection;
    }

    public function close(){
        $this->connection->close();
    }

}
