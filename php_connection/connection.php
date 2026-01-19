<?php
    $conn= mysqli_connect("127.0.0.1","root","root","MovieFlix","3306");
    if(!$conn){
        die("Connection failed: " . mysqli_connect_error());
    }
    
?>