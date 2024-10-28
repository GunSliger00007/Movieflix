<?php
header('Content-Type: application/json');
include '../php_connection/connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Ensure the id is an integer
    $sql = "SELECT * FROM reviews WHERE review_id = $id"; // Correct the query with proper interpolation
    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            $reviews = $result->fetch_assoc();
            echo json_encode(['success' => true, 'review' => $reviews]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Review not found']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Query failed']);
    }
}
?>
