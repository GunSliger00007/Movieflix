<?php
 class SentimentService
{
    private $positiveWords = ['good','great','excellent','amazing','love','fantastic'];
    private $negativeWords = ['bad','boring','worst','terrible','hate'];

    public function analyze($text)
    {
        $text = strtolower($text);
        $words = preg_split('/\s+/', $text);

        $score = 0;
        foreach ($words as $word) {
            if (in_array($word, $this->positiveWords)) $score++;
            if (in_array($word, $this->negativeWords)) $score--;
        }

        return $score;
    }
}
?>