# train_sentiment_model.py
import pandas as pd
import re
import pickle
from sklearn.model_selection import train_test_split
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.naive_bayes import MultinomialNB


def clean_text(text):
    text = text.lower()  # lowercase
    text = re.sub(r"<.*?>", "", text)  # remove HTML tags
    text = re.sub(r"[^a-zA-Z0-9\s]", "", text)  # remove special chars
    text = re.sub(r"\s+", " ", text).strip()  # remove extra spaces
    return text

# ----------- Load your CSV file -----------------------
csv_path = "reviews.csv"  # path to your CSV
df = pd.read_csv(csv_path)

print("Columns found:", df.columns)

# Ensure correct column names
if "review" not in df.columns or "sentiment" not in df.columns:
    raise Exception("CSV must have 'review' and 'sentiment' columns")

# Clean text
df['review_clean'] = df['review'].astype(str).apply(clean_text)

# Convert sentiment to numeric: positive=1, negative=0
df['sentiment_num'] = df['sentiment'].map({'positive': 1, 'negative': 0})

# ----------- Split data for training/testing -----------
X_train, X_test, y_train, y_test = train_test_split(
    df['review_clean'], df['sentiment_num'], test_size=0.2, random_state=42
)

# ----------- Vectorize text ---------------------------
vectorizer = TfidfVectorizer()
X_train_vec = vectorizer.fit_transform(X_train)
X_test_vec = vectorizer.transform(X_test)

classifier = MultinomialNB()
classifier.fit(X_train_vec, y_train)

accuracy = classifier.score(X_test_vec, y_test)
print(f"Test Accuracy: {accuracy:.4f}")

model_data = {
    "vectorizer": vectorizer,
    "classifier": classifier
}

with open("sentiment_model.pkl", "wb") as f:
    pickle.dump(model_data, f)

print("Model saved as sentiment_model.pkl")
