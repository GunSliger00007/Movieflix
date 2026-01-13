<?php
// Include your RecommendationService class
require 'RecommendationService.php';

// Connect to database
   $conn= mysqli_connect("127.0.0.1","root","","MovieFlix","3308");
    if(!$conn){
        die("Connection failed: " . mysqli_connect_error());
    }

// Create the RecommendationService instance
$service = new RecommendationService($conn);

// Test user ID
$userId = 1; // change to any existing user in your database

// Call the method
$recommendedMovies = $service->recommendForUser($userId);

// Print results
echo "<pre>";
print_r($recommendedMovies);
echo "</pre>";
?>
