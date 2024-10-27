<?php
header('Content-Type: application/json');
include '../php_connection/connection.php'; 

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM movies WHERE movie_id = $id"; 
    $result = $conn->query($sql); 
    
    if ($result) { 
        if ($result->num_rows > 0) {
            $movie = $result->fetch_assoc();
            echo json_encode(['success' => true, 'movie' => $movie]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Movie not found']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Database query failed']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid ID']);
}
?>
