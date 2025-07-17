import sys
import json
import re
import spacy
from pdfminer.high_level import extract_text

# Load Spacy NLP Model
nlp = spacy.load("en_core_web_sm")

def extract_resume_data(resume_path):
    try:
        # Extract text from PDF
        text = extract_text(resume_path)
        doc = nlp(text.lower())

        # Extract CGPA using regex
        cgpa_match = re.search(r'cgpa\s*[:\-]?\s*(\d+(\.\d+)?)', text, re.IGNORECASE)
        cgpa = float(cgpa_match.group(1)) if cgpa_match else 0.0

        # Extract skills using a predefined list
        predefined_skills = ["python", "java", "c++", "machine learning", "data science", "sql", "html", "css", "javascript"]
        skills = [token.text for token in doc if token.text in predefined_skills]

        # Extract certifications (assuming they are mentioned with "certification" in the text)
        certifications = [ent.text for ent in doc.ents if "certification" in ent.text]

        return {
            "cgpa": cgpa,
            "skills": skills,
            "certifications": certifications
        }
    except Exception as e:
        return {"error": str(e)}

if __name__ == "__main__":
    resume_path = sys.argv[1]
    result = extract_resume_data(resume_path)
    print(json.dumps(result, indent=4))
