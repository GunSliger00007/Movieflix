<?php
include("../php_connection/connection.php"); // Include the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get the review_id safely
    $review_id = $_POST['review_id'];

    // 1. Get the movie_id related to this review
    $stmt = $conn->prepare("SELECT movie_id FROM reviews WHERE review_id = ?");
    $stmt->bind_param("i", $review_id);
    $stmt->execute();
    $stmt->bind_result($movie_id);
    $stmt->fetch();
    $stmt->close();

    if ($movie_id) {
        // 2. Delete recommendations related to this movie
        $stmt = $conn->prepare("DELETE FROM movie_recommendations WHERE movie_id = ?");
        $stmt->bind_param("i", $movie_id);
        $stmt->execute();
        $stmt->close();
    }

    // 3. Delete the review itself
    $stmt = $conn->prepare("DELETE FROM reviews WHERE review_id = ?");
    $stmt->bind_param("i", $review_id);
    $stmt->execute();
    $stmt->close();

    // 4. Redirect back to reviews page
    header("Location: ../dashboard/reviews.php");
    exit();
}

// Close the connection
$conn->close();
?>
