<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }
        input, select, button {
            padding: 6px;
        }
        .actions {
            margin-top: 15px;
        }
    </style>
</head>
<body>

<h2>Product Order</h2>

<form method="POST" action="">
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th class="weightClass" style="visibility: hidden;">Weight</th>
                <th>Type</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            <!-- Row 1 -->
            <tr>
                <td>
                    <input type="text" name="product" required>
                </td>
                <td>
                    <input type="number" name="price" step="0.01" required>
                </td>
                <td style="visibility:hidden">
                    <input class="weightClass" type="text" name="weight" value="0" placeholder="0">
                </td>
                <td>
                    <select name="type" onchange="changeType()">
                        <option value="digital">Digital</option>
                        <option value="physical">Physical</option>
                    </select>
                </td>
                <td>
                    <input type="number" name="quantity" min="1" value="1">
                </td>
            </tr>

        </tbody>
    </table>

    <div class="actions">
        <button type="submit"> Send to Server</button>
        <button onclick="goHomePage()">Back</button>
    </div>
</form>
<script>
    function changeType() {
        var x = document.querySelector("select").value;
        console.log(document.querySelectorAll(".weightClass"));
        if(x === 'physical'){
            document.querySelectorAll(".weightClass").forEach(el => {
                console.log(el);
                el.style.visibility = 'visible';
            });
        } else {
            document.querySelectorAll(".weightClass").forEach(el => {
                console.log(el);
                if(el.style.visibility == 'visible'){
                    el.style.visibility = 'hidden';
                }
            });
        }
    };
    function goHomePage() {
        window.location.href = 'home.php';
    }
</script>
<?php

ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
include __DIR__. '/task1/classes/init_db.php';
use task1\classes\init_db\CreateConnection;

$startConnection = new CreateConnection();
$conn = $startConnection->connectDb();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    echo $_POST['product'];
    echo "<hr>";
    echo $_POST['price'];
    echo "<hr>";
    echo $_POST['type'];
    echo "<hr>";
    echo $_POST['quantity'];
    echo "<hr>";
    echo $_POST['weight'];
    echo "<hr>";
    if(checkIfProductExist($_POST['product'],$conn,$startConnection)){
        exit();
    }else{
        $sqlQuery = "INSERT INTO product (product_name, price, type ,weight) VALUES ('".ucfirst($_POST['product'])."', '".$_POST['price']."', '".$_POST['type']."','".intval($_POST['weight'])."')";

        if($conn->query($sqlQuery) === TRUE) {
            echo "New record created successfully";
            $_POST = [];
        } else {
            echo "Error: " . $sqlQuery . "<br>" . $conn->error;
        }
    }
}
function checkIfProductExist($product_name,$conn,$startConnection){
    $sqlQuery = "SELECT * FROM product WHERE product_name = '$product_name' ";
    $result = $conn->query($sqlQuery);
    if($result->num_rows > 0){
        echo "Product already exists.";
        $newQuantity = intval($result->fetch_assoc()['quantity']) + intval($_POST['quantity']);
        $startConnection->updateData($conn, 'product', "quantity = ".$newQuantity.", price = '".$_POST['price']."'", " product_name = '".ucfirst($_POST['product'])."'"); 
        return true;
    }
    return false;
}
?>
</body>
</html>
