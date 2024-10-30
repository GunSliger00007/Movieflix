<?php
include('./php_connection/connection.php');

if (isset($_GET['q'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['q']);

    // Fetch movies that match the search query
    $sql = "SELECT * FROM movies WHERE title LIKE '%$search_query%'";
    $result = $conn->query($sql);

    $movies = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $movies[] = array('movie_id' => $row['movie_id'], 'title' => $row['title']);
        }
    }

    // Return JSON response
    echo json_encode($movies);
}
?>
