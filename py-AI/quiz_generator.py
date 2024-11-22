import openai
import random

# Function to generate quiz questions
def generate_quiz(text_content):
    try:
        # Use GPT to extract the topic from the content
        topic_prompt = f"Based on the following text, what is the main topic of this content?\n\n{text_content}\n\nTopic:"
        
        # Get the topic from GPT
        topic_response = openai.ChatCompletion.create(
            model="gpt-3.5-turbo",
            messages=[{"role": "user", "content": topic_prompt}],
            max_tokens=500
        )
        
        # Extract the inferred topic
        inferred_topic = topic_response.choices[0].message['content'].strip()

        # Generate quiz questions using GPT
        quiz_prompt = f"Please generate quiz questions based on the following content. The content is about {inferred_topic}.\n\n{text_content}\n\nQuestions:"
        
        # Generate quiz questions using GPT
        quiz_response = openai.ChatCompletion.create(
            model="gpt-3.5-turbo",
            messages=[{"role": "user", "content": quiz_prompt}],
            max_tokens=1500
        )

        # Extract the questions from the response
        questions_text = quiz_response.choices[0].message['content'].strip()

        # Split the questions and prepare the multiple-choice format
        questions = questions_text.split("\n")
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
            "topic": inferred_topic,
            "num_questions": len(quiz_data),
            "questions": quiz_data
        }

    except Exception as e:
        return {'error': str(e)}
