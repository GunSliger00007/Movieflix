<?php
include("connection.php"); // Ensure connection.php includes the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $movie_id = $_POST['id']; // Ensure this is the correct name
    $title = $_POST['title'];
    $description = $_POST['description'];
    $release_date = $_POST['release_date'];
    $duration = $_POST['duration'];

    // Retrieve uploaded files
    $movie_file = $_FILES['movie_file'];
    $cover_image = $_FILES['cover_image'];

    // Define upload directories
    $movie_file_dir = 'uploads/movies/';
    $image_file_dir = 'uploads/images/';

    // Create directories if they do not exist
    if (!is_dir($movie_file_dir)) {
        mkdir($movie_file_dir, 0777, true);
    }
    if (!is_dir($image_file_dir)) {
        mkdir($image_file_dir, 0777, true);
    }

    // Initialize file paths
    $movie_file_path = '';
    $cover_image_path = '';

    // Handle movie file upload
    if ($movie_file['error'] === UPLOAD_ERR_OK) {
        $movie_file_path = $movie_file_dir . basename($movie_file['name']);
        if (!move_uploaded_file($movie_file['tmp_name'], $movie_file_path)) {
            echo "Error uploading movie file.<br>";
        }
    } else {
        echo "Error with movie file upload: " . $movie_file['error'] . "<br>";
    }

    // Handle cover image upload
    if ($cover_image['error'] === UPLOAD_ERR_OK) {
        $cover_image_path = $image_file_dir . basename($cover_image['name']);
        if (!move_uploaded_file($cover_image['tmp_name'], $cover_image_path)) {
            echo "Error uploading cover image.<br>";
        }
    } else {
        echo "Error with cover image upload: " . $cover_image['error'] . "<br>";
    }

    // Prepare the update SQL query
    $sql = "UPDATE movies SET 
            title = '$title', 
            description = '$description', 
            release_date = '$release_date', 
            duration = '$duration'";

    // Only update file paths if they were uploaded
    if ($movie_file_path) {
        $sql .= ", file_path = '$movie_file_path'";
    }
    if ($cover_image_path) {
        $sql .= ", cover_image = '$cover_image_path'";
    }

    $sql .= " WHERE movie_id = $movie_id"; // Ensure you update the correct movie

    // Debugging: Print SQL query
    echo "SQL Query for movie update: " . $sql . "<br>";

    if ($conn->query($sql) === TRUE) {
        // Delete existing category associations
        $delete_categories_sql = "DELETE FROM movie_categories WHERE movie_id = $movie_id";
        if ($conn->query($delete_categories_sql)) {
            echo "Categories deleted successfully.<br>";
        } else {
            echo "Error deleting categories: " . $conn->error . "<br>";
        }

        // Debugging: Check if category_id exists and is an array
        if (isset($_POST['category_id'])) {
            echo "Categories received: " . implode(", ", $_POST['category_id']) . "<br>";
        } else {
            echo "No categories selected.<br>";
        }

        // Insert new categories if any are selected
        if (isset($_POST['category_id']) && !empty($_POST['category_id'])) {
            foreach ($_POST['category_id'] as $category_id) {
                $category_sql = "INSERT INTO movie_categories (movie_id, category_id) VALUES ('$movie_id', '$category_id')";
                // Debugging: Print each insert query
                echo "Inserting category SQL: " . $category_sql . "<br>";
                if (!$conn->query($category_sql)) {
                    echo "Error inserting category ID $category_id: " . $conn->error . "<br>";
                } else {
                    echo "Category ID $category_id inserted successfully.<br>";
                }
            }
        } else {
            echo "No categories to insert.<br>";
        }

        // Redirect to the dashboard after successful update
        header("Location: ../dashboard/index.php");
        exit();
    } else {
        echo "Error updating movie: " . $sql . "<br>" . $conn->error;
    }
}
?>
