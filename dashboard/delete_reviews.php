<?php
include("../php_connection/connection.php"); // Include the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the review_id from the POST request
    $review_id = $_POST['review_id'] ;

    // Prepare the SQL query to delete the review
    $sql = "DELETE FROM reviews WHERE review_id = '$review_id'";

    // Execute the delete query
    $conn->query($sql);

    // Redirect after successful deletion
    header("Location: ../dashboard/reviews.php");
    exit();
}

// Close the connection
$conn->close();
