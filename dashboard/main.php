<?php
$conn = new mysqli("127.0.0.1", "root", "", "MovieFlix", "3308");
if ($conn->connect_error) die("DB connection failed: " . $conn->connect_error);

require_once __DIR__ . '/RecommendationService.php';
require_once __DIR__ . '/SentimentService.php';

$movieId = 65; // replace with your movie_id
$recService = new RecommendationService($conn);

// Call getAverageSentiment to calculate and save the sentiment score
$sentimentScore = $recService->getAverageSentiment($movieId);

echo "Sentiment score for movie ID $movieId: $sentimentScore\n";

$conn->close();
?>
