<?php

class Database {
    private $host = "localhost";
    private $db_name = "blog_db";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
        if($this->conn->connect_error){
            die("Connection failed: " . $this->conn->connect_error);
        }
        return $this->conn;
    }
    public function createDb(){
        $sqlQuery = ("CREATE DATABASE IF NOT EXISTS ". $this->db_name);
        $this->conn = new mysqli($this->host, $this->username, $this->password);
        if ($this->conn->query($sqlQuery) === TRUE) {
            echo "Database created successfully";
        } else {
            echo "Error creating database: " . $this->conn->error;
        }
    }

    public function createTables(){
        $userTable = "CREATE TABLE IF NOT EXISTS users (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(30) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(50) UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            
        )";
        $postsTable = "CREATE TABLE IF NOT EXISTS posts (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id INT(6) UNSIGNED NOT NULL,
            title VARCHAR(100) NOT NULL,
            content TEXT NOT NULL,
            categories_id INT(6),
            rating FLOAT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id)
        )";
        $ratingTable = "CREATE TABLE IF NOT EXISTS ratings (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id INT(6) UNSIGNED NOT NULL,
            post_id INT(6) UNSIGNED NOT NULL,
            rating INT(1) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id),
            FOREIGN KEY (post_id) REFERENCES posts(id)
        )";
        $commentTable = "CREATE TABLE IF NOT EXISTS comments (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            post_id INT(6) UNSIGNED NOT NULL,
            user_id INT(6) UNSIGNED NOT NULL,
            comment TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (post_id) REFERENCES posts(id),
            FOREIGN KEY (user_id) REFERENCES users(id)
        )";
        $imagesTable = "CREATE TABLE IF NOT EXISTS images (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            post_id INT(6) UNSIGNED NOT NULL,
            image_path VARCHAR(255) NOT NULL,
            uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (post_id) REFERENCES posts(id)
        )";
        $categoriesTable = "CREATE TABLE IF NOT EXISTS categories (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) NOT NULL UNIQUE,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $middleCategoryTable = "CREATE TABLE IF NOT EXISTS post_categories (
            post_id INT(6) UNSIGNED NOT NULL,
            category_id INT(6) UNSIGNED NOT NULL,
            PRIMARY KEY (post_id, category_id),
            FOREIGN KEY (post_id) REFERENCES posts(id),
            FOREIGN KEY (category_id) REFERENCES categories(id)
        )";
        $userImagesTable = "CREATE TABLE IF NOT EXISTS user_images (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id INT(6) UNSIGNED NOT NULL,
            image_path VARCHAR(255) NOT NULL,
            uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id)
        )";
        $permisionsTable = "CREATE TABLE IF NOT EXISTS permissions (
            id INT(1) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id INT(6) UNSIGNED NOT NULL,
            level_name VARCHAR(50) NOT NULL,
            description TEXT,
            FOREIGN KEY (user_id) REFERENCES users(id)
        )";
        $tables = [
            $userTable,
            $postsTable,
            $ratingTable,
            $commentTable,
            $imagesTable,
            $categoriesTable,
            $middleCategoryTable,
            $userImagesTable,
            $permisionsTable,
        ];
        foreach ($tables as $table){
            if ($this->conn->query($table) === TRUE) {
                echo "Table created successfully";
                echo "<hr>";
            } else {
                echo "Error creating table: " . $this->conn->error;
            }
        }
    }
}
?>