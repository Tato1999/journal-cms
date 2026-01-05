<?php
    ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
    include __DIR__. '/task1/classes/init_db.php';
    use task1\classes\init_db\CreateConnection;

    $startConnection = new CreateConnection();
    $conn = $startConnection->connectDb();
    $orders = $conn->query("SELECT * FROM orders INNER JOIN product ON orders.product_id = product.product_id ORDER BY orders.id ASC");
    function insertDataIntoSold($conn, $startConnection, $orders){
        
        if($orders->num_rows > 0){
            foreach($orders as $order){
                $startConnection->insertData($conn, 'sold', "(".$order['product_id'].", ".$order['final_price'].", ".$order['final_quantity'].", ".$order['final_discount'].")");
                if ($order['final_quantity'] > 0){
                    $newQuantity = intval($order['quantity']) - intval($order['final_quantity']);
                    $startConnection->updateData($conn, 'product', "quantity = ".$newQuantity," product_id = ".$order['product_id']);
                }else{
                    $startConnection->updateData($conn, 'product', "quantity = 0"," product_id = ".$order['product_id']);
                }
            }
        }
    }

    if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['q']) && $_GET['q'] === 'insert'){
        insertDataIntoSold($conn, $startConnection, $orders);
        $deleteOrders = $conn->query("DELETE FROM `orders`");
        echo true;
        exit();
    }
?>