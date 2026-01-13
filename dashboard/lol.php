<?php
$conn = new mysqli("127.0.0.1", "root", "", "MovieFlix", "3308");
if ($conn->connect_error) die("DB connection failed: " . $conn->connect_error);

class SentimentAnalyzer
{
    private $positiveWords = ['good', 'great', 'excellent', 'amazing', 'awesome', 'fantastic', 'love', 'liked', 'enjoyed', 'best', 'loved'];
    private $negativeWords = ['bad', 'terrible', 'awful', 'boring', 'poor', 'hate', 'disliked', 'worst', 'horrible', 'dull'];

    public function calculateScore(array $reviews)
    {
        $totalScore = 0;
        $count = count($reviews);

        foreach ($reviews as $review) {
            $text = strtolower($review);
            $score = 0;

            foreach ($this->positiveWords as $word) {
                $score += substr_count($text, $word);
            }

            foreach ($this->negativeWords as $word) {
                $score -= substr_count($text, $word);
            }

            $totalScore += $score;
        }

        $average = $count > 0 ? $totalScore / $count : 0;
        return max(min($average / 10, 1), -1); // normalize between -1 and 1
    }
}

class RecommendationService
{
    private $conn;
    private $analyzer;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->analyzer = new SentimentAnalyzer();
    }

    public function getReviews($movieId)
    {
        $movieId = (int)$movieId;
        $sql = "SELECT review_text FROM reviews WHERE movie_id = $movieId";
        $result = mysqli_query($this->conn, $sql);
        if (!$result) die("SQL Error in getReviews: " . mysqli_error($this->conn));

        $reviews = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $text = trim($row['review_text']);
            if ($text !== '' && $text !== null) {
                $reviews[] = $text;
            }
        }

        return $reviews;
    }

    // New function: accepts multiple IDs directly
    public function analyzeMultipleMovies(array $movieIds)
    {
        $results = [];

        foreach ($movieIds as $movieId) {
            $reviews = $this->getReviews($movieId);
            $score = empty($reviews) ? 0 : $this->analyzer->calculateScore($reviews);

            $results[$movieId] = [
                'reviews' => $reviews,
                'sentiment_score' => number_format($score, 4)
            ];
        }

        return $results;
    }
}

// Example usage
$service = new RecommendationService($conn);

// Pass multiple IDs directly
$movieIds = [61, 64, 58]; 
$analysis = $service->analyzeMultipleMovies($movieIds);

print_r($analysis);

$conn->close();
?>
