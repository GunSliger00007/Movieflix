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
     * Recommend movies for a user based on their liked movies or preferences
     */
    public function recommendForUser($userId)
    {
        $userId = (int)$userId;

        // 1️⃣ Get the latest review for this user
        $sql = "SELECT movie_id, sentiment_score 
                FROM reviews 
                WHERE user_id = $userId 
                ORDER BY review_id DESC 
                LIMIT 1";
        $result = mysqli_query($this->conn, $sql);

        if (!$result) die("SQL Error: " . mysqli_error($this->conn));

        $row = mysqli_fetch_assoc($result);

        // 2️⃣ If no reviews yet, check for user preferences
        if (!$row) {
            $pref_sql = "SELECT category_id FROM user_preferences WHERE user_id = $userId";
            $pref_result = mysqli_query($this->conn, $pref_sql);
            $preferences = [];
            while ($pref_row = mysqli_fetch_assoc($pref_result)) {
                $preferences[] = (int)$pref_row['category_id'];
            }

            if (!empty($preferences)) {
                $categoryList = implode(',', $preferences);
                $sql = "
                    SELECT m.movie_id, m.title, m.description, m.release_date, m.duration, m.cover_image,
                           IFNULL(AVG(r.rating), 0) as avg_rating,
                           GROUP_CONCAT(DISTINCT c.category_name SEPARATOR ', ') as categories
                    FROM movies m
                    JOIN movie_categories mc ON m.movie_id = mc.movie_id
                    JOIN categories c ON mc.category_id = c.category_id
                    LEFT JOIN reviews r ON m.movie_id = r.movie_id
                    WHERE mc.category_id IN ($categoryList)
                    GROUP BY m.movie_id
                    ORDER BY m.release_date DESC
                    LIMIT 5
                ";
            } else {
                // If no preferences either, recommend latest movies
                $sql = "
                    SELECT m.movie_id, m.title, m.description, m.release_date, m.duration, m.cover_image,
                           IFNULL(AVG(r.rating), 0) as avg_rating,
                           GROUP_CONCAT(DISTINCT c.category_name SEPARATOR ', ') as categories
                    FROM movies m
                    LEFT JOIN movie_categories mc ON m.movie_id = mc.movie_id
                    LEFT JOIN categories c ON mc.category_id = c.category_id
                    LEFT JOIN reviews r ON m.movie_id = r.movie_id
                    GROUP BY m.movie_id
                    ORDER BY m.release_date DESC
                    LIMIT 5
                ";
            }
            $result = mysqli_query($this->conn, $sql);
            $recommended = [];
            while ($movie = mysqli_fetch_assoc($result)) {
                $recommended[] = $movie;
            }
            return $recommended;
        }

        // 3️⃣ If latest review exists but not positive, return empty
        if ((float)$row['sentiment_score'] <= 0.6) {
            return [];
        }

        // 4️⃣ If latest review is positive, use recommendation logic
        $likedMovieId = (int)$row['movie_id'];

        // Get categories of the liked movie
        $sql = "SELECT category_id FROM movie_categories WHERE movie_id = $likedMovieId";
        $result = mysqli_query($this->conn, $sql);
        $likedCategories = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $likedCategories[] = (int)$row['category_id'];
        }
        if (empty($likedCategories)) return [];

        $categoryList = implode(',', $likedCategories);

        // Recommend other movies in same categories excluding latest review
        $sql = "
            SELECT m.movie_id, m.title, m.description, m.release_date, m.duration, m.cover_image,
                   IFNULL(AVG(r.rating), 0) as avg_rating,
                   GROUP_CONCAT(DISTINCT c.category_name SEPARATOR ', ') as categories
            FROM movies m
            JOIN movie_categories mc ON m.movie_id = mc.movie_id
            JOIN categories c ON mc.category_id = c.category_id
            LEFT JOIN reviews r ON m.movie_id = r.movie_id
            LEFT JOIN (
                SELECT movie_id FROM reviews WHERE user_id = $userId ORDER BY review_id DESC LIMIT 1
            ) latest_review ON m.movie_id = latest_review.movie_id
            WHERE mc.category_id IN ($categoryList) AND latest_review.movie_id IS NULL
            GROUP BY m.movie_id
            ORDER BY m.release_date DESC
            LIMIT 5
        ";
        $result = mysqli_query($this->conn, $sql);
        $recommended = [];
        while ($movie = mysqli_fetch_assoc($result)) {
            $recommended[] = $movie;
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
            return 0.65; // fallback neutral
        }

        curl_close($ch);
        $data = json_decode($response, true);
        return isset($data['score']) ? (float)$data['score'] : 0.65;
    }
}
?>
