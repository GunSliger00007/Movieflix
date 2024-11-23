<?php
// Start a session (if using sessions for user authentication)
session_start();
include('./php_connection/connection.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data

    $user_id = $_POST['user_id']; 
    $movie_id = $_POST['movie_id']; 
    $review_text = $_POST['review']; 
    $rating = $_POST['rate']; 

    if (!empty($user_id) && !empty($movie_id) && !empty($rating)) {
       
        $sql = "INSERT INTO reviews (user_id, movie_id, rating, review_text, created_at) 
                VALUES ('$user_id', '$movie_id', '$rating', '$review_text', NOW())";

        if ($conn->query($sql) === TRUE) {
             header("Location: movie_page.php?id=" . $movie_id); // Redirect on success
            
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "All fields are required.";
    }



   

    $sql = "INSERT INTO reviews (user_id, movie_id, rating, review_text, created_at) 
                VALUES ('$user_id', '$movie_id', '$rating', '$review_text', NOW())";

    $result =mysqli_query($conn,$sql);
    if (mysqli_num_rows($result)> 0) {
        echo "Review successfully submitted!";
        // Optionally, redirect to a "thank you" page or the movie's page
        // header("Location: movie_page.php?id=" . $movie_id);
    } else {
        echo "Error: " . $conn->error;
    }

    // Close the connection
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
