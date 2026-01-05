<?php

    ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
    include __DIR__ . '/../classes/db/db_config.php';
    use Database;
    $db = new Database();
    $new_db = $db->getConnection();
    session_Start();
    $sqlQuery = "SELECT posts.id as post_id, title, content, rating, posts.created_at, GROUP_CONCAT(categories.name) as category_name FROM posts INNER JOIN (post_categories) ON post_categories.post_id = posts.id INNER JOIN (categories) ON categories.id = post_categories.category_id WHERE  posts.user_id = '" . $_SESSION['user_id'] . "' GROUP BY posts.id  ORDER BY created_at DESC";
    function getAdminDashboardData($new_db,$sqlQuery) {
        
        $result = $new_db->query($sqlQuery);
        $posts = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $row['category_name'] = explode(",", $row['category_name']);
                $posts[] = $row;
            }
        }

        $sqlQuery2 = "SELECT username, email, created_at, level_name FROM users INNER JOIN permissions ON permissions.user_id = users.id  ORDER BY created_at DESC";
        $result2 = $new_db->query($sqlQuery2);
        $users = array();
        if ($result2->num_rows > 0) {
            while($row = $result2->fetch_assoc()) {
                $users[] = $row;
            }
        }

        $sqlQuery3 = "SELECT categories.id, categories.name, count(post_categories.post_id) as post_count FROM categories LEFT JOIN post_categories ON post_categories.category_id = categories.id GROUP BY categories.id, categories.name";
        $result3 = $new_db->query($sqlQuery3);
        $categories = array();
        if ($result3->num_rows > 0) {
            while($row = $result3->fetch_assoc()) {
                $categories[] = $row;
            }
        }
        $response = array(
            "posts" => $posts,
            "users" => $users,
            "categories" => $categories
        );
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    function checkIfEntryExists($table, $column, $value, $new_db){
        $sqlQuery = "SELECT * FROM " . $table . " WHERE " . $column . " = '" . $value . "'";
        $result = $new_db->query($sqlQuery);
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
    function insertCategory($data,$new_db){
        $sqlQuery = "INSERT INTO categories (name) VALUES ('" . $data['title'] . "')";
        if ($new_db->query($sqlQuery) === TRUE) {
            $sqlQuery3 = "SELECT categories.id, categories.name, count(post_categories.post_id) as post_count FROM categories LEFT JOIN post_categories ON post_categories.category_id = categories.id GROUP BY categories.id, categories.name";
            $result3 = $new_db->query($sqlQuery3);
            $categories = array();
            if ($result3->num_rows > 0) {
                while($row = $result3->fetch_assoc()) {
                    $categories[] = $row;
                }
            }
            echo json_encode(array("status" => "success", "message" => "Category added successfully.", "categories" => $categories));
        } else {
            echo json_encode(array("status" => "error", "message" => "Error: " . $new_db->error));
        }
    }

    function deletePost($data,$new_db,$sqlQuery){
        $imageSqlQuery = "SELECT image_path FROM images WHERE post_id = '" . $data['id'] . "'";
        $imageResult = $new_db->query($imageSqlQuery);
        if ($imageResult->num_rows > 0) {
            $imageRow = $imageResult->fetch_assoc();
            $imagePath = __DIR__ . "/../" . $imageRow['image_path'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        $deleteSqlQuery = "DELETE FROM posts WHERE id = '" . $data['id'] . "'";
        $posts = array();
        if ($new_db->query($deleteSqlQuery) === TRUE) {
            if ($new_db->query($deleteSqlQuery) === TRUE) {
                
                $result = $new_db->query($sqlQuery);
                if ($result->num_rows > 0) {
                    
                    while($row = $result->fetch_assoc()) {
                        $row['category_name'] = explode(",", $row['category_name']);
                        $posts[] = $row;
                    }
                }else{
                    $posts = array();
                }
                
            }
            echo json_encode(array("status" => "success", "message" => "Post deleted successfully.", "posts" => $posts));
        } else {
            echo json_encode(array("status" => "error", "message" => "Error: " . $new_db->error));
        }
    }

    function deleteCategory($data,$new_db){
        $sqlQuery = "DELETE FROM categories WHERE id = '" . $data['id'] . "'";
        if ($new_db->query($sqlQuery) === TRUE) {
            $sqlQuery3 = "SELECT categories.id, categories.name, count(post_categories.post_id) as post_count FROM categories LEFT JOIN post_categories ON post_categories.category_id = categories.id GROUP BY categories.id, categories.name";
            $result3 = $new_db->query($sqlQuery3);
            $categories = array();
            if ($result3->num_rows > 0) {
                while($row = $result3->fetch_assoc()) {
                    $categories[] = $row;
                }
            }
            echo json_encode(array("status" => "success", "message" => "Category deleted successfully.", "categories" => $categories));
        } else {
            echo json_encode(array("status" => "error", "message" => "Error: " . $new_db->error));
        }
    }
    if($_SERVER["REQUEST_METHOD"] === "GET"){
        getAdminDashboardData($new_db,$sqlQuery);
    }else if($_SERVER["REQUEST_METHOD"] === "POST"){
        header('Content-Type: application/json');
        if(!empty($_POST)){
            $data = $_POST;
        }else{
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
        }
        if($data['name'] === 'category'){
            if(checkIfEntryExists('categories', 'name', $data['title'], $new_db)){
                echo json_encode(array("status" => "error", "message" => "Category already exists."));
                exit;
            }
            insertCategory($data,$new_db);
        }else if( $data['name'] === 'post'){
            if(!empty($_FILES) || isset($_POST['image_url'])){
                
                if(empty($_FILES)){
                    $image_Uploaded = true;
                    $image_path = $_POST["image_url"] . "";
                }else{
                    $upload_folder = __DIR__ . "/../uploads/" . basename(str_replace(" ","_",$_FILES["image"]["name"]));
                    if (file_exists($upload_folder)) {
                        echo "Sorry, file already exists.";
                        exit;
                    } 
                    $imageFileType = strtolower(pathinfo($upload_folder,PATHINFO_EXTENSION));
                    $image_Uploaded = move_uploaded_file($_FILES["image"]["tmp_name"], $upload_folder);
                    $image_path = "uploads/" . basename(str_replace(" ","_",$_FILES["image"]["name"])) . "";
                }
                $categoriesArray = explode(",", $_POST['category']);
                if(empty(explode(",", $_POST['category']))){
                    echo "Sorry, category is empty.";
                    exit;
                }
                session_start();
                $safe_content = $new_db->real_escape_string($_POST['content']);
                $safe_title = $new_db->real_escape_string($_POST['title']);
                
                $sqlQueryInsert = "INSERT INTO posts (user_id, title, content, categories_id, rating) VALUES ('". $_SESSION['user_id'] ."','". $safe_title ."', '". $safe_content ."','". $categoriesArray[0] ."', 0 )";
                if ($new_db->query($sqlQueryInsert) === TRUE) {
                    if ($image_Uploaded) {
                        $sqlImageUpdate = "INSERT INTO images (post_id,image_path) VALUE (LAST_INSERT_ID(), '".$image_path."' )";
                        $new_db->query($sqlImageUpdate);
                        foreach($categoriesArray as $category_id){
                            $sqlCategoryTable = "INSERT INTO post_categories (post_id, category_id) VALUES (LAST_INSERT_ID(), '". $category_id ."')";
                            $new_db->query($sqlCategoryTable);
                        }
                        $posts_in_add = array();
                        $result = $new_db->query($sqlQuery);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $row['category_name'] = explode(",", $row['category_name']);
                                $posts_in_add[] = $row;
                            }
                        }
                        echo json_encode(array("status" => "success", "message" => "Post Upload successfully.", "posts" => $posts_in_add));
                    } else {
                        echo json_encode(array("status" => "error", "message" => "Sorry, there was an error uploading your file."));
                    }
                } else {
                    echo json_encode(array("status" => "error", "message" => "Error: " . $new_db->error));
                }
                $sqlCategoryTable = "INSERT INTO post_categories (post_id, category_id) VALUES (LAST_INSERT_ID(), ?)";
            }
        }
        
    }else if($_SERVER["REQUEST_METHOD"] === "DELETE"){
        header('Content-Type: application/json');
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        if($data['name'] === 'delete_category'){
            deleteCategory($data,$new_db);
        }else if( $data['name'] === 'delete_post'){
            deletePost($data,$new_db,$sqlQuery);
        }
    }
?>