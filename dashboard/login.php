<?php
session_start();
include("../php_connection/connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $email = $_POST['email'];
    $password = $_POST['password'];  
    
    if (!empty($email) && !empty($password)) {
       
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            if ($password === $user['password']) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];

                header("Location: index.php");
                exit();
            } else {
                echo "<script>alert('Password doesn\'t match')
                window.location.href = 'index.php';</script>";
                
                exit(); 
            }
        } else {
            echo "<script>alert('No user found with that email!')
            window.location.href = 'index.php';</script>";
            
            exit();
        }
    } else {
        echo "<script>alert('All fields are required!')
        window.location.href = 'index.php';</script>";
     
        exit();
    }
}

mysqli_close($conn);
?>
