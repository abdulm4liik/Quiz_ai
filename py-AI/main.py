from flask import Flask, request, jsonify
import pdfplumber
import openai
from dotenv import load_dotenv
import os
import random

# Load environment variables from .env file
load_dotenv()

# Get the OpenAI API key from the environment variables
openai.api_key = os.getenv('OPENAI_API_KEY')

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

def generate_quiz(text_content):
    try:
        # Check if text_content is valid
        if not text_content.strip():
            return {'error': 'No valid content to generate quiz from.'}

        # Crafting the prompt for OpenAI to generate the quiz
        quiz_prompt = f"""
        Generate 10 quiz questions based on the following content. 
        Each question should have one correct answer and three incorrect options.
        Format the output as follows:
        question: <Question text>
        Content: {text_content}
        """

        # OpenAI API call to generate quiz
        quiz_response = openai.ChatCompletion.create(
            model="gpt-3.5-turbo",  # Model to use
            messages=[{"role": "system", "content": "You are a helpful assistant for generating quizzes."},
                      {"role": "user", "content": quiz_prompt}],
            max_tokens=1500,
            temperature=0.7
        )

        # Get the response content
        questions_text = quiz_response['choices'][0]['message']['content'].strip()

        if not questions_text:
            return {'error': 'Generated text is empty from OpenAI API.'}

        # Log the raw response for debugging
        print("Raw OpenAI Response:", questions_text)

        # Split the questions based on lines and filter out empty lines
        questions = questions_text.split("\n")
        questions = [q.strip() for q in questions if q.strip()]  # Remove empty lines

        if not questions:
            return {'error': 'No valid questions generated from OpenAI.'}

        # Prepare the quiz data (only send questions)
        quiz_data = []
        for i, q in enumerate(questions):  # Limit to 10 questions
            quiz_data.append({
                "question": q.replace("question:", "").strip()  # Remove unwanted prefixes
            })

        return quiz_data

    except Exception as e:
        print("Error generating quiz:", str(e))
        return {'error': f"Error generating quiz: {str(e)}"}


@app.route('/generate-response', methods=['POST'])
def generate_quiz_route():
    try:
        # Check if file is provided in the request
        if 'file' not in request.files:
            return jsonify({'error': 'File missing in request.'}), 400

        file = request.files['file']

        if not allowed_file(file.filename):
            return jsonify({'error': 'Invalid file type. Only PDF files are allowed.'}), 400

        # Extract text from the uploaded PDF
        text_content = extract_text_from_pdf(file)

        if not text_content.strip():
            return jsonify({'error': 'No text extracted from the PDF.'}), 400

        print("Extracted Text Content:", text_content)

        quiz_data = generate_quiz(text_content)
        if 'error' in quiz_data:
            return jsonify(quiz_data), 500

        return jsonify({'quiz': quiz_data})

    except Exception as e:
        return jsonify({'error': str(e)}), 500


@app.route('/check-answers', methods=['POST'])
def check_answers():
    try:
        # Get answers from the request
        data = request.get_json()

        # Validate that answers and questions are provided
        if not data or 'answers' not in data or 'quiz' not in data:
            return jsonify({'error': 'Answers or quiz data is missing.'}), 400

        quiz = data['quiz']
        answers = data['answers']

        # Check if the number of answers matches the number of questions
        if len(answers) != len(quiz):
            return jsonify({'error': 'Mismatch between number of answers and questions.'}), 400

        # Evaluate answers (for now we assume the correct answer for each question)
        result = []
        for i, question in enumerate(quiz):
            correct_answer = f"Correct answer for question {i+1}"  # This should come from your logic or OpenAI response
            is_correct = answers[i].strip().lower() == correct_answer.strip().lower()
            result.append({
                "question": question['question'],
                "user_answer": answers[i],
                "correct_answer": correct_answer,
                "is_correct": is_correct
            })

        return jsonify({"result": result})

    except Exception as e:
        return jsonify({'error': str(e)}), 500


if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=5000)
