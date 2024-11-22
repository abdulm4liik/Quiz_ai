from flask import Flask, request, jsonify
import openai
import pdfplumber
from quiz_generator import generate_quiz
from summarizer import summarize_text

openai.api_key = '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' 
ALLOWED_EXTENSIONS = {'pdf'}
app = Flask(__name__)

# Function to extract text from PDF
def extract_text_from_pdf(file):
    text_content = ""
    with pdfplumber.open(file) as pdf:
        for page in pdf.pages:
            page_text = page.extract_text()
            if page_text:
                text_content += page_text + "\n"
    return text_content

# Function to check if the file extension is allowed (pdf)
def allowed_file(filename):
    return '.' in filename and filename.rsplit('.', 1)[1].lower() in ALLOWED_EXTENSIONS

@app.route('/generate-response', methods=['POST'])
def generate_response():
    try:
        # Check if file is provided and response_type is in the request
        if 'file' not in request.files or 'response_type' not in request.form:
            return jsonify({'error': 'File or response_type missing in request.'}), 400

        file = request.files['file']
        response_type = int(request.form.get('response_type'))

        # Check if file is a valid PDF
        if not allowed_file(file.filename):
            return jsonify({'error': 'Invalid file type. Only PDF files are allowed.'}), 400

        # Extract text from the uploaded PDF
        text_content = extract_text_from_pdf(file)

        if not text_content.strip():
            return jsonify({'error': 'No text extracted from the PDF.'}), 400

        if response_type == 1:
            # Generate quiz questions
            quiz_data = generate_quiz(text_content)
            return jsonify(quiz_data)

        elif response_type == 0:
            # Summarize content
            summary_data = summarize_text(text_content)
            return jsonify(summary_data)

        else:
            return jsonify({'error': 'Invalid response_type. Use 0 for summarization and 1 for quiz generation.'}), 400

    except Exception as e:
        return jsonify({'error': str(e)}), 500

if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=5000)
