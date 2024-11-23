<?php
session_start();
include('./php_connection/connection.php');  // Include your DB connection

header('Content-Type: application/json');  // Set the content type to JSON

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email1'];
    $password = $_POST['password2'];

    // Direct SQL query to check for the user in the database (No hashing, No prepared statement)
    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        // Fetch user data
        $row = mysqli_fetch_assoc($result);

        // Set session for the logged-in user
        $_SESSION['user_id'] = $row['user_id'];  // Assuming 'id' is the primary key
        $_SESSION['username'] = $row['username'];
        $_SESSION['email'] = $row['email'];

        // Send success response in JSON format
        echo json_encode([
            'status' => 'success',
            'message' => 'User logged in successfully',
            'user' => [
                'user_id' => $row['id'],
                'username' => $row['username'],
                'email' => $row['email']
            ]
        ]);
    } else {
        // Send error response in JSON format
        echo json_encode([
            'status' => 'error',
            'email'=>$email,
            'passowrd'=>$password,
            'message' => 'Invalid email or password'
        ]);
    }

    mysqli_close($conn);  // Close the database connection
}
?>
