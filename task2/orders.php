<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Orders Management</title>
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
            margin-bottom: 15px;
        }

        .layout {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 25px;
        }

        /* Orders table */
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

        /* Summary card */
        .card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .card p {
            margin: 10px 0;
            font-size: 14px;
        }

        label {
            font-size: 14px;
            font-weight: bold;
        }

        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 10px;
            text-align: center;
            background: #007bff;
            color: #fff;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
        }

        footer {
            text-align: center;
            padding: 15px;
            color: #777;
            margin-top: 40px;
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
    📦 Orders Management
</header>

<div class="container">
    <div class="layout">

        <div>
            <h2>Orders</h2>
            <?php 
                ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
                include __DIR__. '/task1/classes/init_db.php';
                use task1\classes\init_db\CreateConnection;

                $startConnection = new CreateConnection();
                $conn = $startConnection->connectDb();
                
                // $testData = $startConnection->getTableColumns($conn, 'sold');
                // if($testData->num_rows > 0){
                //     while($row = $testData->fetch_assoc()){
                //         if($row['COLUMN_NAME']){
                //             echo $row['COLUMN_NAME'] ."<br>";
                //         }
                //     }
                // } else {
                //     echo "Table does not exist";
                // }
                $orders = $conn->query("SELECT * FROM orders INNER JOIN product ON orders.product_id = product.product_id ORDER BY orders.id ASC");
                $total_price = 0;
                if($orders->num_rows > 0){
                    ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Piece Price</th>
                                <th>Discount %</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                    <?php
                    foreach($orders as $order){
                        $total_price += intval($order['final_price']);
                        $total_price_in_usd = $total_price;
                        ?>
                            <tbody>
                                <tr>
                                    <td>#<?php echo $order['id'] ?></td>
                                    <td><?php echo $order['product_name'] ?></td>
                                    <td><?php echo $order['final_quantity'] ?></td>
                                    <td><?php echo $order['price'] ?></td>
                                    <td><?php echo $order['final_discount'] ?> %</td>
                                    <td><?php echo $order['final_price'] ?></td>
                                </tr>
                            
                        <?php
                    }
                    ?>
                        </tbody>
                    </table>
            <?php
                }
            ?>
            
        </div>

        
        <div class="card">
            <?php
                $currency = '$';
            ?>
            <h2>Order Summary</h2>

            <p><strong>Total:</strong> <span id="totalPriceAmount" ><?php echo $total_price?></span><span id="totalPriceCurrency" >$</span></p>

            <label>Paid Amount</label>
            <input id="paiedMoney" oninput="calculateExchange()" type="number" placeholder="Enter paid money">
            <label>Currency</label>
            <select>
                <option onclick="selectUsd()" >USD</option>
                <option onclick="selectEur()">EUR</option>
                <option onclick="selectGel()">GEL</option>
            </select>
            <button class="btn" onclick="selectCurrency()">Select Currency</button>
            

            <label>Exchange Rate</label>
            <input id="exchangeMoneyId" type="number" placeholder="e.g. 2.65">

            <a class="btn" onclick="saveOrder()" >Save Order</a>
        </div>

    </div>
</div>
<div class="nav-actions">
    <a href="home.php" class="btn secondary">Return Home Page</a>
    <a href="index.php" class="btn secondary">Return Main Page</a>
    <a href="orders.php" class="btn primary">Complete Order</a>
    <a href="statistics.php" class="btn outline">Enter Statistics</a>
</div>

<script>
    function selectUsd(){
        currency = '$';
        document.getElementById('totalPriceCurrency').innerText = currency;
        <?php
            $total_price = $total_price_in_usd;
        ?>
        document.getElementById('totalPriceAmount').innerText = <?php echo $total_price; ?>;
    }
    function selectEur(){
        currency = "€";
        document.getElementById('totalPriceCurrency').innerText = currency;
        <?php
            $total_price = $total_price_in_usd * 0.85;
        ?>
        document.getElementById('totalPriceAmount').innerText = <?php echo $total_price; ?>;
    }
    function selectGel(){
        currency = "₾";
        document.getElementById('totalPriceCurrency').innerText = currency;
        <?php
            $total_price = $total_price_in_usd * 2.69;
        ?>
        document.getElementById('totalPriceAmount').innerText = <?php echo $total_price; ?>;
    }
    function selectCurrency(){
        if (document.querySelector('select').value === 'EUR'){
            selectEur();
        } else if (document.querySelector('select').value === 'GEL'){
            selectGel();
        }else{
            selectUsd();
        }
        if(document.getElementById('paiedMoney').value*1 > 0){
            calculateExchange();
        }
    }
    function calculateExchange(){
        
        if( document.getElementById('totalPriceAmount').innerText *1 < document.querySelector('#paiedMoney').value*1){
            document.getElementById('exchangeMoneyId').value = document.querySelector('#paiedMoney').value*1 - document.getElementById('totalPriceAmount').innerText*1;
        }
        
    }
    function saveOrder(){
        
        if(document.getElementById('totalPriceAmount').innerText *1 < document.querySelector('#paiedMoney').value*1){
            alert('Exchange: '+ document.getElementById('exchangeMoneyId').value + ' ' + document.getElementById('totalPriceCurrency').innerText);
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);
                    if(this.responseText == "1"){
                        alert('Order saved successfully!');
                        window.location.href = 'home.php';
                    } else {
                        alert('Error saving order!');
                    }
                    console.log(this.responseText);
                }
                };
                xmlhttp.open("GET", "sellOrder.php?q=" + 'insert', true);
                xmlhttp.send();
        } else {
            alert('Paid amount is less than total price!');
        }
    }
</script>

<footer>
    © 2025 Orders System
</footer>

</body>
</html>
