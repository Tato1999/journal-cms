<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password, 'commerce');
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
// var_dump($conn);
// Create database
$add_table1_query = "CREATE TABLE product (
        product_id int(6) AUTO_INCREMENT NOT NULL  PRIMARY KEY,
        product_name VARCHAR(30) NOT NULL,
        price INT(10) NOT NULL,
        weight INT(5),
        type VARCHAR(20) NOT NULL,
        quantity INT(5) DEFAULT 1,
        add_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
$add_table2_query = "CREATE TABLE orders (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id int(6) NOT NULL,
    FOREIGN KEY (product_id) REFERENCES product(product_id),
    final_price INT(10) NOT NULL,
    final_quantity INT(5),
    final_discount INT(5),
    add_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
$add_table3_query = "CREATE TABLE sold (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id int(6) NOT NULL,
    FOREIGN KEY (product_id) REFERENCES product(product_id),
    sold_final_price INT(10) NOT NULL,
    sold_quantity INT(5),
    sold_discount INT(5),
    add_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

$update_table = "ALTER TABLE product ADD COLUMN quantity INT(5) DEFAULT 1 AFTER type";
// $tables = [$add_table1_query, $add_table2_query, $add_table3_query];
$tables = [$update_table];

foreach ($tables as $table){
    // echo $table;
    if ($conn->query($table) === TRUE) {
        echo "Table created successfully";
    } else {
        var_dump($conn->error);
        // echo "Error creating table: " . $conn->error;
        
    }
}


$conn->close();
?>