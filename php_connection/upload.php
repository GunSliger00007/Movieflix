<?php 
    include("connection.php"); // Ensure connection.php includes the database connection
?>

<?php 
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $title = $_POST['title'];
    $description = $_POST['description'];
    $release_date = $_POST['release_date'];
    $genre = $_POST['genre'];
    $duration = $_POST['duration'];
    $file_path = $_POST['file_path'];
    $cover_image = $_POST['cover_image'];

    // SQL query to insert the data into the movies table
    $sql = "INSERT INTO movies (title, description, release_date, genre, duration, file_path, cover_image) 
            VALUES ('$title', '$description', '$release_date', '$genre', '$duration', '$file_path', '$cover_image')";

    // Execute the query and check if it was successful
    if ($conn->query($sql) === TRUE) {
        echo "Movie added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
  }
?>
