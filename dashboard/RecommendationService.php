<?php
class RecommendationService
{
    private $conn;
    private $flaskApiUrl;

    public function __construct($conn, $flaskApiUrl = "http://localhost:5000/predict")
    {
        $this->conn = $conn;
        $this->flaskApiUrl = $flaskApiUrl;
    }

    /**
     * Save a new review and calculate sentiment
     */
    public function saveReview($userId, $movieId, $reviewText)
    {
        $userId = (int)$userId;
        $movieId = (int)$movieId;
        $reviewText = mysqli_real_escape_string($this->conn, $reviewText);

        // 1️⃣ Insert review
        $sql = "INSERT INTO reviews (user_id, movie_id, review_text) VALUES ($userId, $movieId, '$reviewText')";
        if (!mysqli_query($this->conn, $sql)) {
            die("SQL Error inserting review: " . mysqli_error($this->conn));
        }

        // 2️⃣ Get the inserted review ID
        $reviewId = mysqli_insert_id($this->conn);

        // 3️⃣ Get sentiment score from Flask API
        $score = $this->callFlaskApi($reviewText);
        $score = min(max((float)$score, 0), 1);

        // 4️⃣ Update sentiment_score
        $sqlUpdate = "UPDATE reviews SET sentiment_score = $score WHERE review_id = $reviewId";
        mysqli_query($this->conn, $sqlUpdate) or die("SQL Error updating sentiment: " . mysqli_error($this->conn));

        return $reviewId;
    }

    /**
     * Recommend movies for a user based on their liked movies
     */
    public function recommendForUser($userId)
    {
        $userId = (int)$userId; // ensure integer

        // 1️⃣ Get movies the user liked (sentiment_score > 0.7)
        $sql = "SELECT movie_id FROM reviews WHERE user_id = $userId AND sentiment_score > 0.5";
        $result = mysqli_query($this->conn, $sql);
        if (!$result) die("SQL Error: " . mysqli_error($this->conn));

        $likedMovieIds = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $likedMovieIds[] = (int)$row['movie_id'];
        }
        if (empty($likedMovieIds)) return []; // no liked movies

        $likedMovieIdsList = implode(',', $likedMovieIds);

        // 2️⃣ Get categories of liked movies
        $sql = "SELECT DISTINCT category_id FROM movie_categories WHERE movie_id IN ($likedMovieIdsList)";
        $result = mysqli_query($this->conn, $sql);
        if (!$result) die("SQL Error: " . mysqli_error($this->conn));

        $likedCategories = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $likedCategories[] = (int)$row['category_id'];
        }
        if (empty($likedCategories)) return []; // no categories found

        $categoryList = implode(',', $likedCategories);

        // 3️⃣ Recommend movies in same categories excluding already reviewed movies
        $sql = "
            SELECT m.movie_id, m.title, m.description, m.release_date, m.duration, m.file_path, m.cover_image,
                   GROUP_CONCAT(DISTINCT r.review_text SEPARATOR ' | ') AS reviews
            FROM movies m
            JOIN movie_categories mc ON m.movie_id = mc.movie_id
            LEFT JOIN reviews r ON m.movie_id = r.movie_id AND r.sentiment_score > 0.5
            WHERE mc.category_id IN ($categoryList)
              AND m.movie_id NOT IN (
                  SELECT movie_id FROM reviews WHERE user_id = $userId
              )
            GROUP BY m.movie_id, m.title, m.description, m.release_date, m.duration, m.file_path, m.cover_image
        
        ";
        $result = mysqli_query($this->conn, $sql);
        if (!$result) die("SQL Error: " . mysqli_error($this->conn));

        $recommended = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $recommended[] = $row;
        }

        return $recommended;
    }

    /**
     * Update sentiment scores for all reviews using Flask API
     */
    public function updateReviewSentiments()
    {
        $sql = "SELECT review_id, review_text FROM reviews";
        $result = mysqli_query($this->conn, $sql);
        if (!$result) die("SQL Error: " . mysqli_error($this->conn));

        while ($row = mysqli_fetch_assoc($result)) {
            $reviewId = (int)$row['review_id'];
            $text = $row['review_text'];

            // Call Flask API for sentiment
            $score = $this->callFlaskApi($text);

            // Ensure score is float between 0 and 1
            $score = min(max((float)$score, 0), 1);

            $sqlUpdate = "UPDATE reviews SET sentiment_score = $score WHERE review_id = $reviewId";
            mysqli_query($this->conn, $sqlUpdate) or die("SQL Error updating sentiment: " . mysqli_error($this->conn));
        }
    }

    /**
     * Call Flask API for sentiment score
     */
    private function callFlaskApi($text)
    {
        $ch = curl_init($this->flaskApiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['text' => $text]));
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);

        $response = curl_exec($ch);
        if ($response === false) {
            echo "Curl Error: " . curl_error($ch) . "\n";
            curl_close($ch);
            return 0.5; // fallback neutral
        }

        curl_close($ch);
        $data = json_decode($response, true);
        return isset($data['score']) ? (float)$data['score'] : 0.5;
    }
}
?>
