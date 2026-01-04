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
    public function recommend($movieId, $limit = 5)
    {
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

        arsort($scores);
        return array_slice(array_keys($scores), 0, $limit);
    }

    /* ===============================
       PRIVATE METHODS
       =============================== */

    private function buildVector($movieId)
    {
        $vector = [];

        $sql = "
            SELECT c.category_name
            FROM movie_categories mc
            JOIN categories c ON mc.category_id = c.category_id
            WHERE mc.movie_id = $movieId
        ";

        $result = mysqli_query($this->conn, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            $vector[$row['category_name']] = 1;
        }

        return $vector;
    }

    private function cosineSimilarity($v1, $v2)
    {
        $dot = 0;
        $mag1 = 0;
        $mag2 = 0;

        foreach ($v1 as $key => $val) {
            $dot += $val * ($v2[$key] ?? 0);
            $mag1 += $val * $val;
        }

        foreach ($v2 as $val) {
            $mag2 += $val * $val;
        }

        if ($mag1 == 0 || $mag2 == 0) return 0;

        return $dot / (sqrt($mag1) * sqrt($mag2));
    }

    private function getAverageSentiment($movieId)
    {
        $sql = "
            SELECT review_text
            FROM reviews
            WHERE movie_id = $movieId
        ";

        $result = mysqli_query($this->conn, $sql);

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
        $result = mysqli_query(
            $this->conn,
            "SELECT movie_id FROM movies WHERE movie_id != $movieId"
        );

        $movies = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $movies[] = $row;
        }

        return $movies;
    }
}
