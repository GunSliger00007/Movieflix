<?php
header('Content-Type: application/json');
include '../php_connection/connection.php'; 

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Fetch the movie data
    $sql = "SELECT * FROM movies WHERE movie_id = $id"; 
    $movie_result = $conn->query($sql); 

    if ($movie_result && $movie_result->num_rows > 0) {
        $movie = $movie_result->fetch_assoc();

        // Fetch related categories
        $related_categories_query = "
            SELECT c.category_id, c.category_name 
            FROM categories c
            INNER JOIN movie_categories mc ON c.category_id = mc.category_id
            WHERE mc.movie_id = $id
        ";
        $related_result = $conn->query($related_categories_query);
        $related_categories = [];

        while ($row = $related_result->fetch_assoc()) {
            $related_categories[] = $row;
        }

        // Fetch non-related categories
        $non_related_categories_query = "
            SELECT c.category_id, c.category_name 
            FROM categories c
            WHERE c.category_id NOT IN (
                SELECT mc.category_id 
                FROM movie_categories mc
                WHERE mc.movie_id = $id
            )
        ";
        $non_related_result = $conn->query($non_related_categories_query);
        $non_related_categories = [];

        while ($row = $non_related_result->fetch_assoc()) {
            $non_related_categories[] = $row;
        }

        // Prepare the response with both related and non-related categories
        echo json_encode([
            'success' => true,
            'movie' => $movie,
            'related_categories' => $related_categories,
            'non_related_categories' => $non_related_categories
        ]);

    } else {
        echo json_encode(['success' => false, 'message' => 'Movie not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid ID']);
}
?>
