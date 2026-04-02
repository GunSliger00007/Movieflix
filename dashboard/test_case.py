# test_movie_sentiment.py

import pickle

# Load pipeline model
with open("sentiment_model1.pkl", "rb") as f:
    pipeline = pickle.load(f)  # pipeline contains both vectorizer + classifier

# Classification based on score
def classify(score):
    if score >= 0.85:
        return "EXTREMELY POSITIVE 🎉"
    elif score >= 0.7:
        return "POSITIVE 👍"
    elif score <= 0.3:
        return "NEGATIVE 👎"
    else:
        return "NEUTRAL 😐"

# Movie-specific reviews
movie_reviews = [
    # Extremely Positive
    "Absolutely mind-blowing! A masterpiece. Incredible acting, perfect screenplay, loved every second!",
    "Good story extremely well acted. The movie is a masterpiece. I was blown away by the performances and the storyline. A must-watch for everyone!",

    # Positive
    "Really enjoyed the movie, great performances.",
    "Incredible direction, good cinematography and strong storyline.",

    # Neutral / Mixed
    "It was okay, not too good, not too bad.",
    "Decent movie but a bit slow in the middle, some parts were enjoyable.",
    "Average film, some moments were interesting.",

    # Negative
    "I didn't like the movie, it was boring.",
    "Bad storyline and weak acting, wasted my time.",
    "Worst movie ever! Completely terrible and unbearable.",
    "Absolutely horrible, I regret watching it."
]

print("\n🎬 ---- Movie Sentiment Test Results ----\n")

for review in movie_reviews:
    # Use pipeline directly
    score = pipeline.predict_proba([review])[0][1]  # probability for positive class
    label = classify(score)

    print(f"🎥 Review: {review}")
    print(f"⭐ Score : {round(score, 3)}")
    print(f"🏷️ Label : {label}")
    print("-" * 60)