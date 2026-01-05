<?php 
    ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
    
    include __DIR__. '/../classes/db/db_config.php';
    use Database;
    $new_db = new Database();
    echo "Try Connnection...<br>";
    var_dump($new_db->getConnection());
    echo "<hr>";
    echo "Creating Tables...<br>";
    var_dump($new_db->createTables());
    echo "<hr>";
    // $conn = new mysqli(Config::$db_host, Config::$db_username, Config::$db_password);
?>