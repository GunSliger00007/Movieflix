<?php
session_start();
include './php_connection/connection.php';  // Include your database connection



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
        $user_id = $_POST['user_id'];   // Assuming user_id is stored in session after login
        $movie_id = $_POST['movie_id'];    // The movie_id sent via POST

        // Check if the movie is already in the wishlist
        $check_sql = "SELECT * FROM wishlist WHERE user_id = $user_id AND movie_id = $movie_id";
        $result = $conn->query($check_sql);

        if ($result->num_rows > 0) {
            echo "Movie already in wishlist.";
        } else {
            // Add to wishlist
            $sql = "INSERT INTO wishlist (user_id, movie_id) VALUES ($user_id, $movie_id)";
            
            if ($conn->query($sql) === TRUE) {
                echo "Movie added to wishlist successfully.";
            } else {
                echo "Error: " . $conn->error;
            }
        }
    } else {
        echo "No movie selected to add to wishlist.";
}

$conn->close();
?>
