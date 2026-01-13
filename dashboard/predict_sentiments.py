# predict_sentiment.py
import sys
import pickle
import pandas as pd

# Load model and vectorizer
with open("sentiment_model.pkl", "rb") as f:
    model_data = pickle.load(f)
vectorizer = model_data['vectorizer']
classifier = model_data['classifier']

# Read input text from command line
text = sys.argv[1]

# Transform and predict
X = vectorizer.transform([text])
score = classifier.predict_proba(X)[0][1]  # probability for positive class
print(score)  # returns a float between 0 and 1
