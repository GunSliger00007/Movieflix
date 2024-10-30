<?php
include('./php_connection/connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $search_query = mysqli_real_escape_string($conn, $_POST['search_query']);

   
    $sql = "SELECT * FROM movies WHERE title LIKE '%$search_query%'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        echo "<h2>Search Results for: " . htmlspecialchars($search_query) . "</h2>";
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li><a href='movie_details.php?movie_id=" . $row['movie_id'] . "'>" . htmlspecialchars($row['title']) . "</a></li>";
        }
        echo "</ul>";
    } else {
        echo "<h2>No results found for: " . htmlspecialchars($search_query) . "</h2>";
    }
} else {
    echo "<h2>Invalid search request.</h2>";
}

// Close the connection
$conn->close();
?>
