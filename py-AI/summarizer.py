import openai

# Function to summarize the text content
def summarize_text(text_content):
    try:
        # Use GPT to summarize the content
        summarize_prompt = f"Please summarize the following text:\n\n{text_content}\n\nSummary:"
        
        # Generate summary using GPT
        summary_response = openai.ChatCompletion.create(
            model="gpt-3.5-turbo",
            messages=[{"role": "user", "content": summarize_prompt}],
            max_tokens=500
        )

        # Extract the summary from the response
        summary = summary_response.choices[0].message['content'].strip()

        return {
            "summary": summary
        }

    except Exception as e:
        return {'error': str(e)}
