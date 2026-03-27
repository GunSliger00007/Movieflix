<?php
session_start(); 
include('./php_connection/connection.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'];
    $user = $_POST['username'];
    $password = $_POST['password1'];

    // Check if email or username already exists
    $check_sql = "SELECT * FROM users WHERE email = '$email' OR username = '$user'";
    $result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($result) > 0) {
        // Return error if username or email already exists
        echo json_encode(['status' => 'error', 'message' => 'The username or email is already taken.']);
    } else {
        // Insert the data into the users table
        $sql = "INSERT INTO users (email, username, password) VALUES ('$email', '$user', '$password')";

        if (mysqli_query($conn, $sql)) {
            // Return success message in JSON format
            $user_id = mysqli_insert_id($conn);
            $_SESSION['user_id'] = $user_id;  // Store user ID
            $_SESSION['username'] = $user;
            $_SESSION['email'] = $email;

            // Insert preferences if any
            if (isset($_POST['preferences']) && is_array($_POST['preferences'])) {
                foreach ($_POST['preferences'] as $category_id) {
                    $category_id = intval($category_id);
                    $pref_sql = "INSERT INTO user_preferences (user_id, category_id) VALUES ('$user_id', '$category_id')";
                    mysqli_query($conn, $pref_sql);
                }
            }
            echo json_encode(['status' => 'success', 'message' => 'User registered successfully.']);
        } else {
            // Return error message if insert failed
            echo json_encode(['status' => 'error', 'message' => 'Error inserting the data.']);
        }
    }

    mysqli_close($conn);
}
?>
