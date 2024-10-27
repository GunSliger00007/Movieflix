<?php
// Include your database connection file
include '../php_connection/connection.php'; // Ensure this file establishes the database connection


if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
  
    $categoryId = $_POST['category_id'];

    $sql = "DELETE FROM categories WHERE category_id = $categoryId";
    if($conn->query($sql)==TRUE){
        header("Location: categories.php");
    }else{
        echo("error");
    }
}
