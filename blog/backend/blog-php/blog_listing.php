<?php 
    ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
    include __DIR__ . '/classes/db/db_config.php';
    use Database;
    $db = new Database();
    $new_db = $db->getConnection();

    function getAllPosts($new_db){
        if(isset($_GET['id'])){
            $sqlQuery = "SELECT posts.id as post_id, title, content, rating, posts.created_at, GROUP_CONCAT(categories.name) as category_name, images.image_path as image_path FROM posts INNER JOIN (images) ON images.post_id = posts.id INNER JOIN (post_categories) ON post_categories.post_id = posts.id INNER JOIN (categories) ON categories.id = post_categories.category_id WHERE post_categories.category_id = ".$_GET['id']." GROUP BY posts.id  ORDER BY created_at DESC";
        }else{
            $sqlQuery = "SELECT posts.id as post_id, title, content, rating, posts.created_at, GROUP_CONCAT(categories.name) as category_name, images.image_path as image_path FROM posts INNER JOIN (images) ON images.post_id = posts.id INNER JOIN (post_categories) ON post_categories.post_id = posts.id INNER JOIN (categories) ON categories.id = post_categories.category_id GROUP BY posts.id  ORDER BY created_at DESC";
        }
        
        $result = $new_db->query($sqlQuery);
        $posts = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $row['category_name'] = explode(",", $row['category_name']);
                if(str_contains($row['image_path'],"http")){
                    $row['image_path'] = $row['image_path'];
                }else{
                    if(str_contains(gethostbyname(gethostname()),gethostname())){
                        $ipAddr = "127.0.0.1";
                    }else{
                        $ipAddr = gethostbyname(gethostname());
                    }
                    $row['image_path'] = "http://".$ipAddr."/test/blog/backend/blog-php/" . $row['image_path'];
                } 
                $posts[] = $row;
            }
        }
        echo json_encode(array("status" => "success", "message" => "Fetch all posts.", "posts" => $posts));
    }

    if($_SERVER["REQUEST_METHOD"] == "GET"){
        getAllPosts($new_db);
    }
?>