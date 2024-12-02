from flask import Flask, request, jsonify
import pdfplumber
import openai
from dotenv import load_dotenv
import os
import json

load_dotenv()

openai.api_key = os.getenv('OPENAI_API_KEY')

ALLOWED_EXTENSIONS = {'pdf'}
app = Flask(__name__)

def extract_text_from_pdf(file):
    text_content = ""
    with pdfplumber.open(file) as pdf:
        for page in pdf.pages:
            page_text = page.extract_text()
            if page_text:
                text_content += page_text + "\n"
    return text_content.strip()

def allowed_file(filename):
    return '.' in filename and filename.rsplit('.', 1)[1].lower() in ALLOWED_EXTENSIONS

def generate_quiz(text_content):
    try:
        if not text_content.strip():
            return {'error': 'No valid content to generate quiz from.'}

        quiz_prompt = f"""
        Generate 10 quiz questions based on the following content.
        Each question should include:
        - A question text.
        - Four options (in an array).
        - The index of the correct answer in the options array.
        Format the response as a valid JSON array like this:
        [
            {{
                "question_number": "1"
                "question": "Example question?",
                "options": ["Option 1", "Option 2", "Option 3", "Option 4"],
                "correct_answer": 0
            }}
        ]
        Content: {text_content}
        """

        quiz_response = openai.ChatCompletion.create(
            model="gpt-3.5-turbo",
            messages=[ 
                {"role": "system", "content": "You are a helpful assistant for generating quizzes."},
                {"role": "user", "content": quiz_prompt}
            ],
            max_tokens=2000,
            temperature=0.7
        )

        questions_data = quiz_response['choices'][0]['message']['content'].strip()

        if not questions_data:
            return {'error': 'Generated text is empty from OpenAI API.'}

        if questions_data.startswith("```json"):
            questions_data = questions_data[7:]  
        if questions_data.endswith("```"):
            questions_data = questions_data[:-3] 

        try:
            quiz_data = json.loads(questions_data)
        except json.JSONDecodeError as e:
            print("JSON parsing error:", str(e))
            return {'error': f"JSON parsing error: {str(e)}"}

        if not isinstance(quiz_data, list) or not all(
            isinstance(q, dict) and 
            'question' in q and 'options' in q and 'correct_answer' in q
            for q in quiz_data
        ):
            return {'error': 'Invalid format in generated quiz data.'}

        return quiz_data

    except Exception as e:
        print("Error generating quiz:", str(e))
        return {'error': f"Error generating quiz: {str(e)}"}
def generate_summary(text_content):
    try:
        if not text_content.strip():
            return {'error': 'No valid content to summarize.'}

        summary_prompt = f"""
        Summarize the following content into a detailed and thorough explanation that covers all the key points, providing a full and clear understanding.
        Format the response as plain text, with a complete and well-explained summary.
        Content: {text_content}
        """


        summary_response = openai.ChatCompletion.create(
            model="gpt-3.5-turbo",
            messages=[ 
                {"role": "system", "content": "You are a helpful assistant for summarizing text and focusing on key points."},
                {"role": "user", "content": summary_prompt}
            ],
            max_tokens=1000,
            temperature=0.5
        )

        summary = summary_response['choices'][0]['message']['content'].strip()

        if not summary:
            return {'error': 'Generated summary is empty from OpenAI API.'}

        # Return only the summary as the key part of the response
        return {'summary': summary}

    except Exception as e:
        print("Error generating summary:", str(e))
        return {'error': f"Error generating summary: {str(e)}"}



@app.route('/generate-response', methods=['POST'])
def generate_quiz_route():
    try:
        if 'file' not in request.files:
            return jsonify({'error': 'File missing in request.'}), 400

        file = request.files['file']

        if not allowed_file(file.filename):
            return jsonify({'error': 'Invalid file type. Only PDF files are allowed.'}), 400

        text_content = extract_text_from_pdf(file)

        if not text_content:
            return jsonify({'error': 'No text extracted from the PDF.'}), 400

        print("Extracted Text Content:", text_content)

        response_type = int(request.form.get('response_type', 1))  

        if response_type == 1:
            quiz_data = generate_quiz(text_content)
            if 'error' in quiz_data:
                return jsonify(quiz_data), 500
            return jsonify({'quiz': quiz_data})

        elif response_type == 0:
            summary_data = generate_summary(text_content)
            if 'error' in summary_data:
                return jsonify(summary_data), 500
            return jsonify({'summary': summary_data})

        else:
            return jsonify({'error': 'Invalid response type. Use 0 for summary and 1 for quiz.'}), 400

    except Exception as e:
        return jsonify({'error': str(e)}), 500

if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=5000)
