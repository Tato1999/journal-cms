<?php
    ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
    header("Access-Control-Allow-Origin: http://localhost"); 
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");

    ini_set('session.cookie_samesite', 'Lax');
    include __DIR__ . '/classes/db/db_config.php';
    use Database;
    $db = new Database();
    $new_db = $db->getConnection();

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sqlQuery = "SELECT *, level_name FROM users  INNER JOIN (permissions) ON users.id = permissions.user_id WHERE username = '$username'";
    $result = $new_db->query($sqlQuery);
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        if(password_verify($password, $row['password'])){
            ini_set('session.cookie_samesite', 'Lax');
            session_start();
            if(!isset($_SESSION['user_id'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
            }
            if($row['level_name'] == 'Admin'){
                $_SESSION['is_admin'] = true;
                echo "<script>window.location.href = 'http://localhost/test/blog/blog-template/admin/dashboard.html';</script>";
            }
            echo "<script>window.location.href = 'http://localhost/test/blog/blog-template/index.html';</script>";
        }else{
            echo "<script>window.location.href = 'http://localhost/test/blog/blog-template/login.html?error=incorrect_password';</script>";
        }
    }else{
        echo "<script>window.location.href = 'http://localhost/test/blog/blog-template/login.html?error=user_not_found';</script>";
    }

?>