<?php
include("../php_connection/connection.php"); // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    // Get the movie ID from the GET request
    $movie_id = $_GET['id'];

    // Fetch the movie details to get file paths
    $sql = "SELECT file_path, cover_image FROM movies WHERE movie_id = $movie_id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $movie_file_path = $row['file_path'];
        $cover_image_path = $row['cover_image'];

        // Delete the movie record from the database
        $delete_sql = "DELETE FROM movies WHERE movie_id = $movie_id";
        if ($conn->query($delete_sql) === TRUE) {

            // Delete the movie file only (not the folder)
            if (file_exists($movie_file_path) && is_file($movie_file_path)) {
                unlink($movie_file_path);
            }

            // Delete the cover image file only (not the folder)
            if (file_exists($cover_image_path) && is_file($cover_image_path)) {
                unlink($cover_image_path);
            }

            // Redirect to the movie list page after successful deletion
            header("Location: ../dashboard/index.php");
            exit();
        } else {
            echo "Error deleting movie record.";
        }
    } else {
        echo "Movie not found.";
    }

    $conn->close();
} else {
    echo "Invalid request method or missing movie ID.";
}
?>
