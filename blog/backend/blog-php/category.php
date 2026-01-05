<?php 
    ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
    include __DIR__ . '/classes/db/db_config.php';
    use Database;
    $db = new Database();
    $new_db = $db->getConnection();

    if($_SERVER['REQUEST_METHOD'] == "GET"){
        $getCategoryQuery = "SELECT categories.id as category_id, categories.name as category_name, COUNT(post_categories.category_id) as post_count FROM categories LEFT JOIN (post_categories) ON post_categories.category_id = categories.id GROUP BY categories.id";
        $categoryResult = $new_db->query($getCategoryQuery);
        $categories = array();
        if($categoryResult->num_rows>0){
            while($row = $categoryResult->fetch_assoc()){
                $categories[] = $row;
            };
        }
        echo json_encode(array("status" => "success", "message" => "Fetch all Category.", "categories" => $categories));
    }
?>