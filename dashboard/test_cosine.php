<?php
// db_connection.php
$servername = "localhost";
$username = "root";
$password = ""; // your MySQL password
$dbname = "your_database"; // replace with your DB name

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// test_cosine_db.php
if (!isset($argv[1])) {
    die("Usage: php test_cosine_db.php <movie_id>\n");
}

$movieId = (int)$argv[1];

/**
 * Build category vector for a movie
 */
function buildVector($conn, $movieId) {
    $vector = [];
    $sql = "
        SELECT c.category_name
        FROM movie_categories mc
        JOIN categories c ON mc.category_id = c.category_id
        WHERE mc.movie_id = $movieId
    ";
    $result = mysqli_query($conn, $sql);
    if (!$result) die("SQL Error in buildVector: " . mysqli_error($conn));

    while ($row = mysqli_fetch_assoc($result)) {
        $vector[$row['category_name']] = 1;
    }

    return $vector;
}

/**
 * Cosine similarity function
 */
function cosineSimilarity($v1, $v2) {
    $dot = 0; $mag1 = 0; $mag2 = 0;

    foreach ($v1 as $key => $val) {
        $dot += $val * ($v2[$key] ?? 0);
        $mag1 += $val * $val;
    }
    foreach ($v2 as $val) $mag2 += $val * $val;

    return ($mag1 == 0 || $mag2 == 0) ? 0 : $dot / (sqrt($mag1) * sqrt($mag2));
}

// 1️⃣ Build vector for target movie
$targetVector = buildVector($conn, $movieId);

// 2️⃣ Get all other movies
$sql = "SELECT movie_id, title FROM movies WHERE movie_id != $movieId";
$result = mysqli_query($conn, $sql);
if (!$result) die("SQL Error in getAllMovies: " . mysqli_error($conn));

echo "Cosine similarity of movies with Movie ID $movieId:\n\n";

while ($row = mysqli_fetch_assoc($result)) {
    $otherVector = buildVector($conn, $row['movie_id']);
    $similarity = cosineSimilarity($targetVector, $otherVector);

    // Only show movies that share at least one category
    if ($similarity > 0) {
        echo "Movie ID: {$row['movie_id']}, Title: {$row['title']}, Similarity: " . round($similarity, 4) . "\n";
    }
}

mysqli_close($conn);
?>
