<?php
  include('./php_connection/connection.php');

  $category = $_GET['category'];
  $year = $_GET['year'];
  
  // Start the base query
  $query = "SELECT m.movie_id, m.title, m.cover_image, m.release_date, c.category_name 
            FROM movies m
            LEFT JOIN movie_categories mc ON m.movie_id = mc.movie_id
            LEFT JOIN categories c ON mc.category_id = c.category_id WHERE 1";

  // Add filter conditions if present
  if (!empty($category)) {
    $query .= " AND c.category_name = ?";
  }

  if (!empty($year)) {
    $query .= " AND YEAR(m.release_date) = ?";
  }

  // Prepare and execute the query using prepared statements
  $stmt = $conn->prepare($query);

  if (!empty($category) && !empty($year)) {
    $stmt->bind_param('si', $category, $year);  // 's' for string, 'i' for integer
  } elseif (!empty($category)) {
    $stmt->bind_param('s', $category);  // Only bind the category
  } elseif (!empty($year)) {
    $stmt->bind_param('i', $year);  // Only bind the year
  }

  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        
        // Movie card output
        echo '<a href="play.php?id=' . $row['movie_id'] . '" class="card">';
        echo '<img src="./php_connection/' . $row['cover_image'] . '" alt="Movie Cover Image">';
        echo '<h1>' . $row['title'] . '</h1>';
        echo '<div class="genre">';
        echo '<span>' . $row['category_name'] . '</span>';  // Display category_name
        echo '<div class="rate">';
        
        // Placeholder for rating, replace with actual calculation if needed
        echo '<img src="assets/images/Frame (1).svg" width="11.41" height="10.85">';
        echo '<h5>0.0</h5>';
        
        echo '</div>';
        echo '</div>';
        echo '</a>';
    }
  } else {
    echo 'No movies found matching your filters.';
  }
?>
