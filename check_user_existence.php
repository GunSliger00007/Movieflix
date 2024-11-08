<?php
include('./php_connection/connection.php');

// Get email and username from the AJAX request
$email = $_POST['email'];
$username = $_POST['username'];

// Check if the email or username already exists in the database
$sql = "SELECT * FROM users WHERE email = '$email' OR username = '$username'";
$result = mysqli_query($conn, $sql);

// Check if any results are returned
if (mysqli_num_rows($result) > 0) {
    echo json_encode(['status' => 'error', 'message' => 'The username or email is already taken.']);
} else {
    echo json_encode(['status' => 'success', 'message' => 'Username and email are available.']);
}

mysqli_close($conn);
?>
