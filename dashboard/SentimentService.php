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
     * Generate recommendations based on category and sentiment
     */
    public function recommend($movieId)
    {
        $movieId = (int)$movieId;

        // 1️⃣ Recalculate sentiment for the current movie
        $currentSentiment = $this->getAverageSentiment($movieId);

        // Only proceed if sentiment > 0.5
        if ($currentSentiment <= 0.5) {
            return []; // no recommendations
        }

        // 2️⃣ Get the category of the current movie
        $category = $this->getMovieCategory($movieId);
        if (!$category) return []; // no category found

        // 3️⃣ Get all movies in the same category with sentiment > 0.5, excluding current movie
        $sql = "
            SELECT m.movie_id
            FROM movies m
            JOIN movie_sentiments ms ON m.movie_id = ms.movie_id
            JOIN movie_categories mc ON m.movie_id = mc.movie_id
            WHERE mc.category_id = $category
              AND ms.avg_sentiment > 0.5
              AND m.movie_id != $movieId
        ";
        $result = mysqli_query($this->conn, $sql);
        if (!$result) die("SQL Error in fetching similar movies: " . mysqli_error($this->conn));

        $recommended = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $recommended[] = (int)$row['movie_id'];
        }

        // 4️⃣ Store recommendations in recs_json
        $json = json_encode($recommended);
        $sqlInsert = "
            INSERT INTO movie_recommendations (movie_id, recs_json, updated_at)
            VALUES ($movieId, '$json', NOW())
            ON DUPLICATE KEY UPDATE
                recs_json = '$json',
                updated_at = NOW()
        ";
        mysqli_query($this->conn, $sqlInsert) or die("SQL Error storing recommendations: " . mysqli_error($this->conn));

        return $recommended;
    }

    /* ===============================
       PRIVATE METHODS
       =============================== */

    /**
     * Get category ID of a movie
     */
    private function getMovieCategory($movieId)
    {
        $sql = "SELECT category_id FROM movie_categories WHERE movie_id = $movieId";
        $result = mysqli_query($this->conn, $sql);
        if (!$result) die("SQL Error in getMovieCategory: " . mysqli_error($this->conn));

        $row = mysqli_fetch_assoc($result);
        return $row ? (int)$row['category_id'] : null;
    }

    /**
     * Recalculate average sentiment for a movie
     */
    private function getAverageSentiment($movieId)
    {
        $sql = "SELECT review_text FROM reviews WHERE movie_id = $movieId";
        $result = mysqli_query($this->conn, $sql);
        if (!$result) die("SQL Error in getAverageSentiment: " . mysqli_error($this->conn));

        $totalScore = 0;
        $count = 0;

        while ($row = mysqli_fetch_assoc($result)) {
            $score = $this->callFlaskApi($row['review_text']);
            $totalScore += $score;
            $count++;
        }

        $average = $count > 0 ? $totalScore / $count : 0.5; // fallback neutral
        $averageFormatted = number_format($average, 4, '.', '');

        // Store/update sentiment
        $sqlInsert = "
            INSERT INTO movie_sentiments (movie_id, avg_sentiment)
            VALUES ($movieId, $averageFormatted)
            ON DUPLICATE KEY UPDATE avg_sentiment = $averageFormatted
        ";
        mysqli_query($this->conn, $sqlInsert) or die("SQL Error updating sentiment: " . mysqli_error($this->conn));

        return $average;
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
            $err = curl_error($ch);
            curl_close($ch);
            error_log("Flask API curl error: $err");
            return 0.5; // neutral fallback
        }

        curl_close($ch);

        $data = json_decode($response, true);
        if (!$data || !isset($data['score'])) {
            error_log("Flask API invalid response: $response");
            return 0.5;
        }

        return (float)$data['score'];
    }
}
?>
