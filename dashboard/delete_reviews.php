<?php
include("../php_connection/connection.php"); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $review_id = $_POST['review_id'];

    
    $stmt = $conn->prepare("SELECT movie_id FROM reviews WHERE review_id = ?");
    $stmt->bind_param("i", $review_id);
    $stmt->execute();
    $stmt->bind_result($movie_id);
    $stmt->fetch();
    $stmt->close();

  

   
    $stmt = $conn->prepare("DELETE FROM reviews WHERE review_id = ?");
    $stmt->bind_param("i", $review_id);
    $stmt->execute();
    $stmt->close();

    
    header("Location: ../dashboard/reviews.php");
    exit();
}


$conn->close();
?>
