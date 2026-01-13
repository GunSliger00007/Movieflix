<?php
include("../php_connection/connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $category_name = trim($_POST['category_name']);

    // Check duplicate
    $checkSql = "SELECT category_id FROM categories WHERE category_name = '$category_name'";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        header("Location: categories.php?error=exists");
        exit;
    }

    // Insert category
    $sql = "INSERT INTO categories (category_name) VALUES ('$category_name')";

    if ($conn->query($sql) === TRUE) {
        header("Location: categories.php?success=1");
        exit;
    } else {
        header("Location: categories.php?error=failed");
        exit;
    }
}
?>
