<?php
session_start();
include('./php_connection/connection.php');  // Include your DB connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check for the user in the database
    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Set session for the logged-in user
        $_SESSION['username'] = $email;
        header('Location: index.php');  // Redirect to a dashboard or home page
        exit();
    } else {
        // If login fails, show error message
        $error = "Invalid email or password.";
    }

    mysqli_close($conn);  // Close the database connection
}
?>