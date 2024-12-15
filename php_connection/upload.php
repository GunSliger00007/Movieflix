Share


You said:
<?php
include("connection.php"); // Ensure connection.php includes the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Retrieve form data
  $title = $_POST['title'];
  $description = $_POST['description'];
  $release_date = $_POST['release_date'];
  $genre = $_POST['genre'];
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

  // Handle movie file upload with error debugging
  if ($movie_file['error'] === UPLOAD_ERR_OK) {
    $movie_file_path = $movie_file_dir . basename($movie_file['name']);
    if (move_uploaded_file($movie_file['tmp_name'], $movie_file_path)) {
      echo "Movie file uploaded successfully.<br>";
    } else {
      echo "Error moving movie file.<br>";
    }
  } else {
    echo "Error uploading movie file: " . $movie_file['error'] . "<br>";
  }

  // Handle cover image upload with error debugging
  if ($cover_image['error'] === UPLOAD_ERR_OK) {
    $cover_image_path = $image_file_dir . basename($cover_image['name']);
    if (move_uploaded_file($cover_image['tmp_name'], $cover_image_path)) {
      echo "Cover image uploaded successfully.<br>";
    } else {
      echo "Error moving cover image.<br>";
    }
  } else {
    echo "Error uploading cover image: " . $cover_image['error'] . "<br>";
  }

  // Insert movie information into the database
  $sql = "INSERT INTO movies (title, description, release_date, genre, duration, file_path, cover_image) 
          VALUES ('$title', '$description', '$release_date', '$genre', '$duration', '$movie_file_path', '$cover_image_path')";

  if ($conn->query($sql) === TRUE) {
    // Get the last inserted movie ID
    $movie_id = $conn->insert_id;

    // Process categories if any are selected
    if (isset($_POST['category_id']) && !empty($_POST['category_id'])) {
      foreach ($_POST['category_id'] as $category_id) {
        // Insert the movie categories into movie_categories table
        $category_sql = "INSERT INTO movie_categories (movie_id, category_id) VALUES ('$movie_id', '$category_id')";
        if (!$conn->query($category_sql)) {
          echo "Error inserting category ID $category_id: " . $conn->error . "<br>";
        }
      }
    }

    header("Location: ../dashboard/index.php");
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}
?>