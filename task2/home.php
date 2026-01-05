<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Catalog</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f4f6f8;
        }

        header {
            background: #222;
            color: #fff;
            padding: 15px 20px;
            font-size: 20px;
        }
        /* BUTTON CONTAINER */
        .nav-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        /* BASE BUTTON */
        .btn {
            display: inline-block;
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            transition: background 0.2s ease, color 0.2s ease;
        }

        /* BUTTON VARIANTS */
        .btn.primary {
            background: #2b6cb0;
            color: #ffffff;
        }

        .btn.primary:hover {
            background: #1e4f8a;
        }

        .btn.secondary {
            background: #6b7280;
            color: #ffffff;
        }

        .btn.secondary:hover {
            background: #4b5563;
        }

        .btn.outline {
            background: transparent;
            color: #2b6cb0;
            border: 2px solid #2b6cb0;
        }

        .btn.outline:hover {
            background: #2b6cb0;
            color: #ffffff;
        }

        /* RESPONSIVE */
        @media (max-width: 600px) {
            .nav-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }

        .container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .products {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 20px;
        }

        .product {
            background: #fff;
            border-radius: 8px;
            padding: 16px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .product h3 {
            margin: 0 0 10px;
        }

        .product p {
            margin: 6px 0;
            font-size: 14px;
            color: #444;
        }

        .badge {
            display: inline-block;
            margin-top: 8px;
            padding: 4px 10px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 6px;
            color: #fff;
            margin-top: 5px;
        }

        .physical {
            background: #28a745;
        }

        .digital {
            background: #17a2b8;
        }

        .btn {
            margin-top: 15px;
            padding: 10px;
            text-align: center;
            background: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn:hover {
            background: #0056b3;
        }

        footer {
            text-align: center;
            padding: 15px;
            color: #777;
            margin-top: 40px;
        }
        /* ORDER PRODUCT CARD */
        form {
            background: #ffffff;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
        }

        /* LEFT CONTENT */
        form > div {
            flex: 1;
        }

        #order_name {
            margin: 0 0 6px;
            font-size: 18px;
            font-weight: 600;
        }

        form p {
            margin: 4px 0;
            font-size: 14px;
            color: #555;
        }

        /* INPUTS */
        form label {
            display: block;
            margin-top: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }

        form input[type="number"] {
            width: 120px;
            padding: 6px 8px;
            margin-top: 4px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }


        /* SUBMIT BUTTON */
        form button {
            align-self: flex-end;
            background: #2b6cb0;
            color: #ffffff;
            border: none;
            padding: 10px 14px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            white-space: nowrap;
        }

        form button:hover {
            background: #1e4f8a;
        }

        /* RESPONSIVE */
        @media (max-width: 700px) {
            form {
                flex-direction: column;
                align-items: stretch;
            }

            form button {
                width: 100%;
                margin-top: 10px;
            }

            form input[type="number"] {
                width: 100%;
            }
        }

    </style>
</head>
<body>

<header>
    🛒 Product Store
</header>

<div class="container">
    <div class="products">

        <?php

            ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
            include __DIR__. '/task1/classes/init_db.php';
            use task1\classes\init_db\CreateConnection;

            $startConnection = new CreateConnection();
            $conn = $startConnection->connectDb();
            $sqlQuery = "SELECT * FROM product WHERE quantity > 0";
            if($conn->query($sqlQuery)->num_rows > 0){
                $row = $conn->query($sqlQuery)->fetch_assoc();
            }

            foreach($conn->query($sqlQuery) as $row){
                ?>
                <?php
                $type_for_if = $row['type'];
                if($row['type'] == 'physical'){
                    $row['type'] = '<span class="badge physical">Physical</span>';
                    
                } else {
                    $row['type'] = '<span class="badge digital">Digital</span>';
                    ?>
                    <?php
                }
                ?>
                <div class="product">
                <form action="" method="post">
                    <div>
                        <input type="text" style="display: none;"  name = "product_id" value="<?php echo $row['product_id']?>">
                        <h3 id = "order_name"><?php echo $row['product_name']?></h3>
                        <p>💲 Price: <?php echo $row['price']?></p>
                        <p id="weightId" style="display: none;" value = "<?php echo $row['weight'] ?>" >⚖️ Weight: <?php echo $row['weight']?> kg</p>
                        <p>📦 Quantity: <?php echo $row['quantity']?></p>
                        <label for="order_quantity">Order Quantity:</label>
                        <input type="number" id="order_quantity" name="order_quantity" min="1" max="<?php echo $row['quantity']?>" value="1">
                        <label for="discount">Set Discount (%):</label>
                        <input type="number" name="discount" min="0" max="100" step="5" value="0">
                        <span class="badge"><?php echo $row['type']?></span>
                        
                    </div>
                
                    <button type="submit" value="<?php echo $row['product_name']?>" >Add to Order</button>
                </form>

                
            </div>
            <?php 
            }
            ?>
            
            <script>
                document.querySelectorAll('#weightId').forEach (el =>  {
                    if(el.getAttribute('value')*1 == 0){
                        el.style.display = 'none';
                    } else {
                        el.style.display = 'block';
                    }
                })
            </script>
    </div>
    <div class="nav-actions">
        <a href="home.php" class="btn secondary">Return Home Page</a>
        <a href="index.php" class="btn secondary">Return Main Page</a>
        <a href="orders.php" class="btn primary">Complete Order</a>
        <a href="statistics.php" class="btn outline">Enter Statistics</a>
    </div>
</div>
<?php
    ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
    include __DIR__ .'/task1/abstract/AbstractProduct.php';
    include __DIR__. '/task1/classes/Classes.php';
    
    use task1\classes\Classes\DigitalProducts;
    use task1\classes\Classes\PhysicalProducts;
    
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        $sqlQuery = "SELECT * FROM product WHERE product_id = ".$_POST['product_id'];
        $productRow = $conn->query($sqlQuery)->fetch_assoc();
        echo "<br>";
        echo $_POST['discount'];
        echo "<hr>";
        
        if($productRow['type'] == 'physical'){
            $productObj = new PhysicalProducts(
                intval($productRow['product_id']),
                $productRow['product_name'],
                intval($productRow['price']),
                intval($_POST['order_quantity']),
                intval($productRow['weight'])
            );
        }else{
            $productObj = new DigitalProducts(
                intval($productRow['product_id']),
                $productRow['product_name'],
                intval($productRow['price']),
                intval($_POST['order_quantity'])
            );
        };
        var_dump($productObj);
        if(intval($_POST['discount']) > 0){
            echo "Discount set to: " . $_POST['discount'] . "% <br>";
            $productObj->setDiscount(intval($_POST['discount']));
        }
        echo "Final Price: " . $productObj->getFinalPrice() . " GEL<br>";
        echo "<hr>";
        if($productObj){
            $sqlQuery = "INSERT INTO orders (product_id, final_price, final_quantity, final_discount) VALUES ('".intval($productObj->id)."', '".$productObj->getFinalPrice()."', '".$_POST['order_quantity']."','".$productObj->getDiscount()."')";
            if($conn->query($sqlQuery) === TRUE) {
                echo "Order placed successfully";
                $_POST = [];
            } else {
                echo "Error: " . $sqlQuery . "<br>" . $conn->error;
            }
        }
    }
?>

<footer>
    © 2025 Product Store
</footer>

</body>
</html>
