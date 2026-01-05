<?php 
    ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
    include __DIR__ . '/classes/db/db_config.php';
    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = trim($_POST['email']); 
    $email = strtolower($email); 
    $hash = md5($email);

    $gravatar_url = "https://www.gravatar.com/avatar/" . $hash . "?d=robohash";

    use Database;
    $new_db = new Database();
    $new_db->getConnection();
    $sqlQuery = "INSERT INTO users (username, password, email) VALUES ('" . trim($_POST['username']) . "', '" . $password_hash . "', '" . $_POST['email'] . "')";

    $sqlQuery2 = "INSERT INTO user_images (user_id, image_path) VALUES ((SELECT id FROM users WHERE username = '".$_POST['username']."'), '" . $gravatar_url . "')";

    $sqlQuery3 = "INSERT INTO permissions (user_id, level_name) VALUES ((SELECT id FROM users WHERE username = '".$_POST['username']."'), 'Admin')";

    $allQuery = [$sqlQuery, $sqlQuery2, $sqlQuery3];
    $trueCount = 0;
    foreach ($allQuery as $query) {
        if ($new_db->conn->query($query) === TRUE) {
            $trueCount++;
        } else {
            echo "<script>window.location.href = 'http://localhost/test/blog/blog-template/register.html?error=error';</script>";
        }
    }
    if ($trueCount === count($allQuery)) {
        // echo "All records created successfully";
        echo "<script>window.location.href = 'http://localhost/test/blog/blog-template/login.html';</script>";
    } else {
        echo "<script>window.location.href = 'http://localhost/test/blog/blog-template/register.html?error=error';</script>";
    }
?>