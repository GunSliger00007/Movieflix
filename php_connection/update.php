<?php
include("connection.php"); // Ensure connection.php includes the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $release_date = $_POST['release_date'];
    $genre = $_POST['genre'];
    $duration = $_POST['duration'];

    // Fetch the existing movie data to get the current file paths
    $sql = "SELECT file_path, cover_image FROM movies WHERE movie_id = $id";
    $result = $conn->query($sql);
    $currentMovie = $result->fetch_assoc();

    // Define upload directories
    $movie_file_dir = 'uploads/movies/';
    $image_file_dir = 'uploads/images/';

    // Handle movie file upload
    if ($_FILES['movie_file']['error'] === UPLOAD_ERR_OK) {
        // Delete the old movie file if it exists
        if (file_exists($currentMovie['file_path'])) {
            unlink($currentMovie['file_path']); // Delete old file
        }
        // Move new file
        $movie_file_path = $movie_file_dir . basename($_FILES['movie_file']['name']);
        move_uploaded_file($_FILES['movie_file']['tmp_name'], $movie_file_path);
    } else {
        $movie_file_path = $currentMovie['file_path']; // Keep the existing file path if no new file is uploaded
    }

    // Handle cover image upload
    if ($_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
        // Delete the old cover image if it exists
        if (file_exists($currentMovie['cover_image'])) {
            unlink($currentMovie['cover_image']); // Delete old cover image
        }
        // Move new cover image
        $cover_image_path = $image_file_dir . basename($_FILES['cover_image']['name']);
        move_uploaded_file($_FILES['cover_image']['tmp_name'], $cover_image_path);
    } else {
        $cover_image_path = $currentMovie['cover_image']; // Keep the existing cover image path if no new image is uploaded
    }

    // Update movie information in the database
    $sql = "UPDATE movies 
            SET title = '$title', description = '$description', release_date = '$release_date', 
                genre = '$genre', duration = '$duration', 
                file_path = '$movie_file_path', cover_image = '$cover_image_path' 
            WHERE movie_id = $id";

    if ($conn->query($sql) === TRUE) {
        // Redirect to the dashboard after updating the movie
        header('Location: ../dashboard/dashboard.php'); // Move one directory back and into dashboard
        exit(); // Ensure no further code is executed after redirection
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
