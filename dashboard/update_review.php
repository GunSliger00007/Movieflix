<?php
include("../php_connection/connection.php"); // Include the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $review_id = $_POST['review_id'] ?? '';   // Review ID
    $user_id = $_POST['user_id'] ?? '';       // Updated User ID
    $movie_id = $_POST['movie_id'] ?? '';     // Updated Movie ID
    $rating = $_POST['rating'] ?? '';         // Updated Rating
    $review_text = $_POST['review_text'] ?? ''; // Updated Review Text

    // Check if required fields are provided

    // Prepare the SQL query to update the review
    $sql = "UPDATE reviews 
                SET user_id = '$user_id', movie_id = '$movie_id', rating = '$rating', review_text = '$review_text'
                WHERE review_id = '$review_id'";

    // Execute the update query
    if ($conn->query($sql) === TRUE) {
        // Redirect after successful update
        header("Location: ../dashboard/reviews.php");
        exit();
    } else {
        echo "Error: " . $conn->error; // Show error if update fails
    }
} else {
    echo "All fields are required."; // Display message if fields are missing
}


// Close the connection
$conn->close();
