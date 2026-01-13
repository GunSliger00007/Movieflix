from flask import Flask, request, jsonify
import pickle

app = Flask(__name__)

# Load pre-trained model & vectorizer once
with open("sentiment_model.pkl", "rb") as f:
    model_data = pickle.load(f)

vectorizer = model_data['vectorizer']
classifier = model_data['classifier']

@app.route('/predict', methods=['POST'])
def predict_sentiment():
    data = request.get_json()

    if not data or 'text' not in data:
        return jsonify({"error": "Missing 'text' field"}), 400

    text = data['text']

    # Transform & predict
    X = vectorizer.transform([text])
    score = classifier.predict_proba(X)[0][1]  # probability for positive class

    # Debug logging
    print(f"[DEBUG] Received text: {text}")
    print(f"[DEBUG] Predicted score: {score:.3f}")

    return jsonify({"score": round(float(score), 3), "raw_score": float(score)})

if __name__ == '__main__':
    app.run(debug=True)
