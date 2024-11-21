<?php
// Enable error reporting to catch issues
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session at the beginning
session_start();

include("../php_connection/connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Fetch email and password from the form
    $email = $_POST['email'];
    $password = $_POST['password'];  
    
    // Ensure both fields are filled
    if (!empty($email) && !empty($password)) {
       
        // Use prepared statements to prevent SQL injection
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            // Compare the plain-text password
            if ($password === $user['password']) {
                // Login successful, store user data in session
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];

                // Redirect to dashboard
                header("Location: index.php");
                exit();
            } else {
                // Invalid password
                echo "Invalid password!";
            }
        } else {
            // Email not found
            echo "No user found with that email!";
        }
    } else {
        // Handle empty fields
        echo "All fields are required!";
    }
}

// Close the database connection
mysqli_close($conn);
?>
