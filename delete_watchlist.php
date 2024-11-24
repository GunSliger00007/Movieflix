<?php
include('./php_connection/connection.php'); // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the movie_id and user_id from the POST request
    $movie_id = $_POST['movie_id'];
    $user_id = $_POST['user_id'];

    // Check if the movie is in the wishlist
    $check_sql = "SELECT * FROM wishlist WHERE movie_id = '$movie_id' AND user_id = '$user_id'";
    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {
        // Delete the movie from the wishlist
        $delete_sql = "DELETE FROM wishlist WHERE movie_id = '$movie_id' AND user_id = '$user_id'";

        if ($conn->query($delete_sql) === TRUE) {
            header('Location:watchlist.php');
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Movie not found in wishlist.";
    }
} else {
    echo "Invalid request method.";
}
?>
