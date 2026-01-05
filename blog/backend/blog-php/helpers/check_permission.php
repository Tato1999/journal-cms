<?php
    ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
    include __DIR__ . '/../classes/db/db_config.php';
    use Database;
    $db = new Database();
    $new_db = $db->getConnection();
    function checkPermission($user_id, $required_level, $new_db){
        $sqlQuery = "SELECT level_name FROM permissions WHERE user_id = '" . $user_id . "'";
        $result = $new_db->query($sqlQuery);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user_level = $row['level_name'];
            $levels = array("User" => 1, "Editor" => 2, "Admin" => 3);
            if($levels[$user_level] >= $levels[$required_level]){
                return true;
            }else{
                return false;
            }
        } else {
            return false;
        }
    }
    function checkLevel($id, $new_db){
        $sqlQuery = "SELECT * FROM permissions WHERE user_id = '" . $id . "'";
        $result = $new_db->query($sqlQuery);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                return $row['level_name'];
            }
        } else {
            return false;
        }
    }
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        session_start();
        if(!isset($_SESSION['user_id'])) {
            echo json_encode(array("status" => "error", "message" => "User not logged in."));
            exit;
        }
        $user_id = $_SESSION['user_id'];
        $required_level = $_POST['required_level'];
        if(checkPermission($user_id, $required_level, $new_db)){
            echo json_encode(array("status" => "success", "message" => "Permission granted."));
        }else{
            echo json_encode(array("status" => "error", "message" => "Permission denied."));
        }
    }else if($_SERVER["REQUEST_METHOD"] === "GET"){
        session_start();
        if(!isset($_SESSION['user_id'])) {
            echo json_encode(array("status" => "error", "message" => "User not logged in."));
            exit;
        }
        $user_id = $_SESSION['user_id'];
        echo json_encode(array("status" => "success", "level" => checkLevel($user_id, $new_db)));
    }
?>