<?php
include("../php_connection/connection.php"); // Include the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $user_id = $_POST['user_id'];
    $movie_id = $_POST['movie_id'];
    $rating = $_POST['rating'];
    $review_text = $_POST['review_text'];
    echo $user_id;
    echo "movie". $movie_id;
    echo $rating;
    echo $review_text;

    // Simple check to ensure required fields are provided
    if (!empty($user_id) && !empty($movie_id) && !empty($rating)) {
        // Insert review into the database
        $sql = "INSERT INTO reviews (user_id, movie_id, rating, review_text, created_at) 
                VALUES ('$user_id', '$movie_id', '$rating', '$review_text', NOW())";

        if ($conn->query($sql) === TRUE) {
            header("Location: ../dashboard/reviews.php"); // Redirect on success
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "All fields are required.";
    }
}
?>
