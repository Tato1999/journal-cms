<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Statistics</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f8;
        }

        header {
            background: #1f2933;
            color: #fff;
            padding: 15px 20px;
            font-size: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        h2 {
            margin-bottom: 20px;
        }

        /* STAT CARDS */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .stat-card h3 {
            margin: 0;
            font-size: 14px;
            color: #777;
        }

        .stat-card p {
            margin: 10px 0 0;
            font-size: 24px;
            font-weight: bold;
            color: #222;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: #f0f0f0;
            font-size: 14px;
        }

        /* BADGES */
        .badge {
            padding: 4px 8px;
            font-size: 12px;
            border-radius: 4px;
            color: #fff;
        }

        .gold { background: #ffc107; color: #222; }
        .silver { background: #adb5bd; color: #222; }
        .bronze { background: #cd7f32; }

        footer {
            text-align: center;
            padding: 15px;
            color: #777;
            margin-top: 40px;
        }
        .nav-buttons {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }

        .nav-btn {
            padding: 10px 16px;
            background: #6c757d;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            font-size: 14px;
        }

        .nav-btn.primary {
            background: #007bff;
        }

        .nav-btn:hover {
            opacity: 0.9;
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
    </style>
</head>
<body>

<header>
    📊 Order Statistics
</header>

<?php
    ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
    include __DIR__. '/task1/classes/init_db.php';
    use task1\classes\init_db\CreateConnection;
    $startConnection = new CreateConnection();
    $conn = $startConnection->connectDb();  
    $soldOrders = $startConnection->fetchData($conn, 'sold');
    $pendigOrders = $startConnection->fetchData($conn, 'orders');
?>

<div class="container">
    

    <h2>Overview</h2>

    <div class="stats">
        <div class="stat-card">
            <h3>Total Orders</h3>
            <p><?php echo intval($soldOrders->num_rows) + intval($pendigOrders->num_rows) ?></p>
        </div>

        <div class="stat-card">
            <h3>Total Revenue</h3>
            <p><?php $sqlQuery = "SELECT SUM(sold_price) FROM sold"; $result = $conn->query($sqlQuery); if($result->num_rows > 0){while($row = $result->fetch_assoc()){echo $row['SUM(sold_price)']."$";}} ?></p>
        </div>

        <div class="stat-card">
            <h3>Paid Orders</h3>
            <p><?php echo $soldOrders->num_rows ?></p>
        </div>

        <div class="stat-card">
            <h3>Pending Orders</h3>
            <p><?php echo $pendigOrders->num_rows ?></p>
        </div>
    </div>

    <h2>Sales by Product Type</h2>

    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th>Orders</th>
                <th>Revenue</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Physical</td>
                <td>
                    <?php

                        $sqlQuery = "SELECT COUNT(*) as physical_product_count FROM sold INNER JOIN product ON sold.product_id = product.product_id WHERE product.type = 'physical'";
                        $result = $conn->query($sqlQuery);
                        if($result->num_rows > 0){
                            while($row = $result->fetch_assoc()){
                                echo $row['physical_product_count'];
                            }
                        }
                    ?>
                </td>
                <td>
                    <?php
                        $sqlQuery = "SELECT SUM(sold_price) as physical_product_count FROM sold INNER JOIN product ON sold.product_id = product.product_id WHERE product.type = 'physical'";
                        $result = $conn->query($sqlQuery);
                        if($result->num_rows > 0){
                            while($row = $result->fetch_assoc()){
                                echo $row['physical_product_count']."$";
                            }
                        }
                    ?>
                </td>
            </tr>
            <tr>
                <td>Digital</td>
                <td>
                    <?php

                        $sqlQuery = "SELECT COUNT(*) as digital_product_count FROM sold INNER JOIN product ON sold.product_id = product.product_id WHERE product.type = 'digital'";
                        $result = $conn->query($sqlQuery);
                        if($result->num_rows > 0){
                            while($row = $result->fetch_assoc()){
                                echo $row['digital_product_count'];
                            }
                        }
                    ?>
                </td>
                <td>
                    <?php
                        $sqlQuery = "SELECT SUM(sold_price) as digital_product_count FROM sold INNER JOIN product ON sold.product_id = product.product_id WHERE product.type = 'digital'";
                        $result = $conn->query($sqlQuery);
                        if($result->num_rows > 0){
                            while($row = $result->fetch_assoc()){
                                echo $row['digital_product_count']."$";
                            }
                        }
                    ?>
                </td>
            </tr>
        </tbody>
    </table>

    <h2 style="margin-top: 30px;">Most Popular Products</h2>

    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Product</th>
                <th>Orders</th>
                <th>Revenue</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $slqQuery = "SELECT COUNT(`sold`.product_id) as product_count, product_name, sold.product_id, SUM(sold.sold_price) as sold_price FROM sold INNER JOIN product ON sold.product_id = product.product_id GROUP BY sold.product_id ORDER BY COUNT(sold.product_Id) DESC LIMIT 3";
                $result = $conn->query($slqQuery);
                if($result->num_rows > 0){
                    $rank = 1;
                    foreach($result as $row){
                        ?>
                        <tr>
                            <td><?php echo $rank++ ?></td>
                            <td><?php echo $row['product_name'] ?></td>
                            <td><?php echo $row['product_count'] ?></td>
                            <td><?php echo $row['sold_price'] ?></td>
                            <td><span class="badge">
                                
                                
                            </span></td>
                        </tr>
                        <?php
                    }
                }
                ?>
        </tbody>
    </table>

</div>
<div class="nav-actions">
    <a href="home.php" class="btn secondary">Return Home Page</a>
    <a href="index.php" class="btn secondary">Return Main Page</a>
    <a href="orders.php" class="btn primary">Complete Order</a>
    <a href="statistics.php" class="btn outline">Enter Statistics</a>
</div>
<script>
    allTag = document.querySelectorAll('.badge');
    allTag.forEach((el,index) =>{
        console.log(index);
        if(index == 0){
            el.innerText = 'Top Seller';
            el.classList.add('gold');
        }
        if(index == 1){
            el.innerText = 'Popular';
            el.classList.add('silver');
        }
        if(index == 2){
            el.innerText = 'Trending';
            el.classList.add('bronze');
        }
    })
</script>
<footer>
    © 2025 Orders System – Statistics
</footer>

</body>
</html>
