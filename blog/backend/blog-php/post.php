<?php
    ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
    include __DIR__ . '/classes/db/db_config.php';
    use Database;
    $db = new Database();
    $new_db = $db->getConnection();

    function getAllComment($id,$new_db){

    }

    function getPosts($id,$new_db){
        $sqlQuery = "SELECT posts.id as post_id, title, content, rating, posts.created_at, GROUP_CONCAT(categories.name) as category_name, images.image_path as image_path, users.id as user_id, users.username as user_name, user_images.image_path as user_image FROM posts INNER JOIN (users) ON users.id = posts.user_id INNER JOIN (user_images) ON user_images.user_id = users.id INNER JOIN (images) ON images.post_id = posts.id INNER JOIN (post_categories) ON post_categories.post_id = posts.id INNER JOIN (categories) ON categories.id = post_categories.category_id WHERE posts.id = $id";
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
        // COUNT(comments.id) as count_of_comments,
        $commentSqlQuery = "SELECT  comments.id as comment_id, comments.comment as comment, comments.created_at as comment_created_at, users.id as user_id, users.username as user_name, user_images.image_path as user_image, ratings.rating as rating FROM comments LEFT JOIN (users) ON users.id = comments.user_id INNER JOIN (user_images) ON user_images.user_id = users.id LEFT JOIN (ratings) ON ratings.comment_id = comments.id  WHERE comments.post_id = $id GROUP BY comments.id ORDER BY comments.created_at DESC";
        
        $resultComment = $new_db->query($commentSqlQuery);
        $comments = array();
        if($resultComment->num_rows>0){
            while($row = $resultComment->fetch_assoc()){
                $comments[] = $row;
            }
        }

        $ratingSqlQuery = "SELECT AVG(ratings.rating) as rating_value, COUNT(ratings.id) as rating_counts FROM ratings WHERE ratings.post_id = $id";
        $resultRatings = $new_db->query($ratingSqlQuery);
        $ratings = array();
        if($resultRatings->num_rows>0){
            while($row = $resultRatings->fetch_assoc()){
                $ratings[] = $row;
            }
        }

        echo json_encode(array("status" => "success", "message" => "Fetch all posts.", "posts" => $posts, "comments" => $comments, "ratings" => $ratings));
    }
    if($_SERVER["REQUEST_METHOD"] === "GET"){
        getPosts($_GET['id'],$new_db);
    }else if($_SERVER["REQUEST_METHOD"] === "POST"){
        sendComment($_POST,$new_db);
    }
    function sendComment($data,$new_db){
        session_start();
        $sqlQueryInsertComment = "INSERT INTO comments (post_id, user_id, comment) VALUES ('" . $data['post_id'] . "', '" . $_SESSION['user_id'] . "', '" . $data['comment'] . "')";
        $comments_array = array();
        $comment_id = 0;
        if ($new_db->query($sqlQueryInsertComment) === TRUE) {
            $comment_id = $new_db->insert_id;
            $selectCommentSqlQuery = "SELECT COUNT(comments.id) as count_of_comments, comments.id as comment_id, comments.comment as comment, comments.created_at as comment_created_at, users.id as user_id, users.username as user_name, user_images.image_path as user_image FROM comments INNER JOIN (users) ON users.id = comments.user_id INNER JOIN (user_images) ON user_images.user_id = users.id WHERE comments.post_id = '" . $data['post_id'] . "' ORDER BY comments.created_at DESC";
            $resultComment = $new_db->query($selectCommentSqlQuery);
            if($resultComment->num_rows>0){
                while($row = $resultComment->fetch_assoc()){
                    $comments_array[] = $row;
                }
            }
        } else {
            echo json_encode(array("status" => "error", "message" => "Error adding comment: " . $new_db->error));
        }
        $sqlInsertRating = "INSERT INTO ratings (post_id, user_id, comment_id, rating) VALUES ('" . $data['post_id'] . "', '" . $_SESSION['user_id'] . "', '". $comment_id. "', '" . $data['rating'] . "')";

        if ($new_db->query($sqlInsertRating) === TRUE) {
            echo json_encode(array("status" => "success", "message" => "Rating added successfully."));
        } else {
            echo json_encode(array("status" => "error", "message" => "Error adding rating: " . $new_db->error));
        }

    }
?>