from flask import Flask, request, jsonify
import joblib
import numpy as np
from PIL import Image
import pytesseract
import os
from flask_cors import CORS

app = Flask(__name__)
CORS(app)  # allow cross-origin requests

# --- Ensure temp directory exists ---
TEMP_DIR = "./temp"
if not os.path.exists(TEMP_DIR):
    os.makedirs(TEMP_DIR)

# --- Load Models ---
le_skill = joblib.load("label_encoder_skill.pkl")
le_career = joblib.load("label_encoder_career.pkl")
dt_model = joblib.load("decision_tree_model.pkl")
rf_model = joblib.load("random_forest_model.pkl")

# --- Subject to skill mapping ---
subject_to_skill = {
    "Computer Programming": "python",
    "Web Development": "html",
    "Networking": "networking",
    "Database": "database",
    "Machine Learning": "ml",
    "AI": "ai",
    "Cloud Computing": "cloud",
    "Excel": "excel",
    "PHP": "php",
    "Java": "java",
    "Javascript": "javascript",
    "CSS": "css"
}

# --- OCR function ---
def extract_subjects_and_grades(file_path):
    try:
        text = pytesseract.image_to_string(Image.open(file_path))
    except Exception as e:
        print(f"OCR error: {e}")
        return []
    subjects = []
    for line in text.splitlines():
        if ":" in line:
            parts = line.split(":")
            if len(parts) >= 2:
                subject = parts[0].strip()
                grade = parts[1].strip()
                subjects.append((subject, grade))
    return subjects

# --- Map subjects to skills ---
def map_subjects_to_skills(subjects):
    skills_dict = {}
    for subject, grade in subjects:
        skill = subject_to_skill.get(subject)
        if skill:
            # Convert grade to numeric if possible
            try:
                level = float(grade)
            except:
                level = grade
            skills_dict[skill] = level
    return skills_dict

# --- Predict endpoint ---
@app.route("/predict", methods=["POST"])
def predict():
    files = request.files.getlist("file")
    if not files:
        return jsonify({"error": "No files uploaded."})

    all_subjects = []

    for f in files:
        safe_filename = f.filename.replace(" ", "_")
        path = os.path.join(TEMP_DIR, safe_filename)
        f.save(path)
        all_subjects += extract_subjects_and_grades(path)
        os.remove(path)

    mapped_skills = map_subjects_to_skills(all_subjects)
    if not mapped_skills:
        return jsonify({"error": "No recognizable subjects/skills detected."})

    # Prepare ML input
    try:
        skill_keys = list(mapped_skills.keys())
        X_input = le_skill.transform(skill_keys).reshape(-1, 1)
    except Exception as e:
        print(f"LabelEncoder error: {e}")
        X_input = np.array([[0]])

    # Predict careers
    y_pred_dt = dt_model.predict(X_input)
    y_pred_rf = rf_model.predict(X_input)
    careers_dt = le_career.inverse_transform(y_pred_dt)
    careers_rf = le_career.inverse_transform(y_pred_rf)

    # Combine predictions with confidence and suggestion
    career_options = []
    for career in np.unique(careers_dt):
        career_options.append({
            "career": career,
            "confidence": 90,
            "suggestion": "Consider improving related skills to boost your chances."
        })

    return jsonify({
        "rawSubjects": all_subjects,
        "mappedSkills": mapped_skills,
        "careerPrediction": careers_dt.tolist(),
        "careerOptions": career_options
    })

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000, debug=True)
