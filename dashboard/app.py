# app_sentiment_api.py

from flask import Flask, request, jsonify
import pickle

app = Flask(__name__)

# Load pre-trained pipeline (vectorizer + classifier)
with open("sentiment_model1.pkl", "rb") as f:
    pipeline = pickle.load(f)  # Pipeline handles vectorization + classification

# Classification thresholds
def classify(score):
    if score >= 0.8:
        return "EXTREMELY POSITIVE 🎉"
    elif score >= 0.65:
        return "POSITIVE 👍"
    elif score <= 0.35:
        return "NEGATIVE 👎"
    else:
        return "NEUTRAL 😐"

@app.route('/predict', methods=['POST'])
def predict_sentiment():
    data = request.get_json()
    if not data or 'text' not in data:
        return jsonify({"error": "Missing 'text' field"}), 400

    text = data['text']

    # Predict probability of positive sentiment
    score = pipeline.predict_proba([text])[0][1]
    label = classify(score)

    # Debug logging (optional)
    print(f"[DEBUG] Received text: {text}")
    print(f"[DEBUG] Predicted score: {score:.3f}, Label: {label}")

    return jsonify({"score": round(float(score), 3), "label": label})

if __name__ == '__main__':
    app.run(debug=True)