<?php
session_start();
include './php_connection/connection.php';  // Include your database connection

header('Content-Type: application/json');  // Set the response content type to JSON

$response = array();  // Initialize an array to store the response

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Directly check if user_id and movie_id are provided
    $user_id = $_POST['user_id'] ?? null;
    $movie_id = $_POST['movie_id'] ?? null;

    if (!empty($user_id) && !empty($movie_id)) {
        // Check if the movie is already in the wishlist
        $check_sql = "SELECT * FROM wishlist WHERE user_id = $user_id AND movie_id = $movie_id";
        $result = $conn->query($check_sql);

        if ($result->num_rows > 0) {
            // Movie is already in the wishlist
            $response['status'] = 'exists';
            $response['message'] = 'Movie already in wishlist.';
        } else {
            // Add the movie to the wishlist
            $sql = "INSERT INTO wishlist (user_id, movie_id) VALUES ($user_id, $movie_id)";
            if ($conn->query($sql) === TRUE) {
                $response['status'] = 'success';
                $response['message'] = 'Movie added to wishlist successfully.';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Error: ' . $conn->error;
            }
        }
    } else {
        // If user_id or movie_id is missing
        $response['status'] = 'error';
        $response['message'] = 'Invalid request. Missing movie or user information.';
    }
} else {
    // If request method is not POST
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method. POST required.';
}

// Use json_encode to return the response as JSON
echo json_encode($response);

$conn->close();
?>
