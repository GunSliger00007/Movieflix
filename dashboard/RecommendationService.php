<?php

class RecommendationService
{
    private $conn;
    private $sentimentService;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->sentimentService = new SentimentService();
    }

    /* ===============================
       PUBLIC METHOD
       =============================== */
    public function recommend($movieId, $limit = 5, $store = true)
    {
        $movieId = (int)$movieId;

        // 1️⃣ Try to fetch stored recommendations first
        if ($store) {
            $stored = $this->getStoredRecommendations($movieId);
            if ($stored !== null && count($stored) > 0) {
                return $stored;
            }
        }

        // 2️⃣ Compute recommendations
        $targetVector = $this->buildVector($movieId);
        $scores = [];

        $movies = $this->getAllMoviesExcept($movieId);

        foreach ($movies as $movie) {
            $vector = $this->buildVector($movie['movie_id']);

            $cosine = $this->cosineSimilarity($targetVector, $vector);
            $sentiment = $this->getAverageSentiment($movie['movie_id']);

            // Weight sentiment lightly
            $finalScore = $cosine + (0.1 * $sentiment);

            if ($finalScore > 0) {
                $scores[$movie['movie_id']] = $finalScore;
            }
        }

        // Sort and get top N
        arsort($scores);
        $topMovies = array_slice(array_keys($scores), 0, $limit);

        // 3️⃣ Store recommendations for next time
        if ($store) {
            $this->storeRecommendations($movieId, $topMovies);
        }

        return $topMovies;
    }

    /* ===============================
       PRIVATE METHODS
       =============================== */

    private function buildVector($movieId)
    {
        $movieId = (int)$movieId;
        $vector = [];

        $sql = "
            SELECT c.category_name
            FROM movie_categories mc
            JOIN categories c ON mc.category_id = c.category_id
            WHERE mc.movie_id = $movieId
        ";
        $result = mysqli_query($this->conn, $sql);
        if (!$result) die("SQL Error in buildVector: " . mysqli_error($this->conn));

        while ($row = mysqli_fetch_assoc($result)) {
            $vector[$row['category_name']] = 1;
        }

        return $vector;
    }

    private function cosineSimilarity($v1, $v2)
    {
        $dot = 0; $mag1 = 0; $mag2 = 0;

        foreach ($v1 as $key => $val) {
            $dot += $val * ($v2[$key] ?? 0);
            $mag1 += $val * $val;
        }
        foreach ($v2 as $val) $mag2 += $val * $val;

        return ($mag1 == 0 || $mag2 == 0) ? 0 : $dot / (sqrt($mag1) * sqrt($mag2));
    }

    private function getAverageSentiment($movieId)
    {
        $movieId = (int)$movieId;
        $sql = "SELECT review_text FROM reviews WHERE movie_id = $movieId";
        $result = mysqli_query($this->conn, $sql);
        if (!$result) die("SQL Error in getAverageSentiment: " . mysqli_error($this->conn));

        $totalScore = 0;
        $count = 0;

        while ($row = mysqli_fetch_assoc($result)) {
            $totalScore += $this->sentimentService->analyze($row['review_text']);
            $count++;
        }

        return $count > 0 ? $totalScore / $count : 0;
    }

    private function getAllMoviesExcept($movieId)
    {
        $movieId = (int)$movieId;
        $sql = "SELECT movie_id FROM movies WHERE movie_id != $movieId";
        $result = mysqli_query($this->conn, $sql);
        if (!$result) die("SQL Error in getAllMoviesExcept: " . mysqli_error($this->conn));

        $movies = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $movies[] = $row;
        }

        return $movies;
    }

    /* ===============================
       DATABASE STORAGE METHODS
       =============================== */

    private function storeRecommendations($movieId, $topMovies)
    {
        $movieId = (int)$movieId;
        $json = json_encode($topMovies);

        $sql = "
            INSERT INTO movie_recommendations (movie_id, recs_json, updated_at)
            VALUES ($movieId, '$json', NOW())
            ON DUPLICATE KEY UPDATE
                recs_json = '$json',
                updated_at = NOW()
        ";
        $result = mysqli_query($this->conn, $sql);
        if (!$result) die("SQL Error in storeRecommendations: " . mysqli_error($this->conn));
    }

    private function getStoredRecommendations($movieId)
    {
        $movieId = (int)$movieId;
        $sql = "SELECT recs_json FROM movie_recommendations WHERE movie_id = $movieId";
        $result = mysqli_query($this->conn, $sql);
        if (!$result) die("SQL Error in getStoredRecommendations: " . mysqli_error($this->conn));

        $row = mysqli_fetch_assoc($result);
        return $row ? json_decode($row['recs_json'], true) : null;
    }
}
