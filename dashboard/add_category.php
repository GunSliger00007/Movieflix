<?php
include("../php_connection/connection.php"); // Ensure connection.php includes the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $category_name = $_POST['category_name'];

    // Insert category into the database
    $sql = "INSERT INTO categories (category_name) VALUES ('$category_name')";

    if ($conn->query($sql) === TRUE) {
        echo "New category created successfully.";
        // Optionally redirect to another page
        header("Location: categories.php"); // Redirect to a page that lists categories
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
