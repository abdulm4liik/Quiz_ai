import openai
import random
from dotenv import load_dotenv
import os

# Load environment variables from .env file
load_dotenv()

# Get the OpenAI API key from the environment variables
openai.api_key = os.getenv('OPENAI_API_KEY')

# Function to generate quiz questions
def generate_quiz(text_content):
    try:
        # Ensure there is content to process
        print("Text Content to OpenAI:", text_content[:500])  # Print a snippet for debugging
        if not text_content.strip():
            return {'error': 'No valid content to generate quiz from.'}

        # Generate quiz questions using GPT-3 directly from the content
        quiz_prompt = f"Please generate quiz questions based on the following content.\n\n{text_content}\n\nQuestions:"
        print("Making OpenAI API Request with Prompt:", quiz_prompt)  # Debugging line

        # Generate quiz questions using GPT
        quiz_response = openai.Completion.create(
            model="gpt-3.5-turbo",
            prompt=quiz_prompt,
            max_tokens=1500,
            n=1,
            stop=None,
            temperature=0.7
        )

        print("OpenAI Response:", quiz_response)  # Debugging line

        # Check if the response contains questions
        if not quiz_response.choices:
            return {'error': 'No questions returned from OpenAI.'}

        # Extract the questions from the response
        questions_text = quiz_response.choices[0].text.strip()

        if not questions_text:
            return {'error': 'Generated text is empty.'}

        # Split the questions and prepare the multiple-choice format
        questions = questions_text.split("\n")
        questions = [q.strip() for q in questions if q.strip()]  # Filter out empty lines

        if not questions:
            return {'error': 'No valid questions generated.'}

        quiz_data = []
        for q in questions:
            # Randomly generate some fake answers for the multiple-choice
            correct_answer = q  # Use the question itself as the correct answer for simplicity
            fake_answers = [correct_answer, f"Fake {random.randint(1, 100)}", f"Fake {random.randint(1, 100)}", f"Fake {random.randint(1, 100)}"]
            random.shuffle(fake_answers)  # Shuffle the answers
            correct_index = fake_answers.index(correct_answer)

            quiz_data.append({
                "question": q,
                "answers": fake_answers,
                "correct_index": correct_index
            })

        return {
            "num_questions": len(quiz_data),
            "questions": quiz_data
        }

    except Exception as e:
        return {'error': str(e)}
