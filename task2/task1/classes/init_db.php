<?php
namespace task1\classes\init_db;
use mysqli;
class CreateConnection{
    protected $servername = "localhost";
    protected $username = "root";
    protected $password = "";
    protected $dbname = "commerce";

    public function connectDb(){
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        return $conn;
    }
    public function getTableColumns($conn, $tableName){
        
        $sqlQuery = "SELECT COLUMN_NAME 
                    FROM INFORMATION_SCHEMA.COLUMNS 
                    WHERE TABLE_NAME = '".$tableName."'";
        $result = $conn->query($sqlQuery);
        $columns = [];

        foreach($result->fetch_all() as $row){
            if($row[0] !== "id" && $row[0] !== "add_at"){
                $columns[] = $row[0];
            }
        }
        return "(" . implode(",", $columns) . ")";
    }
    public function insertData($conn, $table, $data){

        $columns = $this->getTableColumns($conn, $table);
        // echo $columns;
        $sqlQuery = "INSERT INTO $table $columns VALUES $data";
        if($conn->query($sqlQuery) === TRUE){
            return true;
        }else{
            return false;
        }
    }
    public function updateData($conn, $table, $setClause, $whereClause){
        $sqlQuery = "UPDATE $table SET $setClause WHERE $whereClause";
        if($conn->query($sqlQuery) === TRUE){
            return true;
        }else{
            return false;
        }
    }
    public function deleteData($conn, $table, $whereClause){
        $sqlQuery = "DELETE FROM $table WHERE $whereClause";
        if($conn->query($sqlQuery) === TRUE){
            return true;
        }else{
            return false;
        }
    }
    public function fetchData($conn, $table){
        $sqlQuery = "SELECT * FROM $table";
        $result = $conn->query($sqlQuery);
        return $result;
    }
    public function closeConnection($conn){
        $conn->close();
    }

}


?>