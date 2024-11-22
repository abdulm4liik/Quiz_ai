<?php

namespace App\Http\Controllers\Api;


use App\Models\ai_response; 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ai_resource;
use GuzzleHttp\Client;

class AIResponseController extends Controller
{
    public function index()
    {
        $aiResponses = ai_response::where('user_id', auth()->id())->get();
    
      
        return response()->json([
            'data' => ai_resource::collection($aiResponses),
        ]);
    }
    public function store(Request $request)
    {
        // Validate the file upload
        $request->validate([
            'pdf' => 'required|file|mimes:pdf|max:102400', // Max size 100MB
        ]);
    
        // Get the uploaded PDF file
        $file = $request->file('pdf');
    
        // Send the request to the Python Flask server
        $client = new Client();
        try {
            $response = $client->post('http://127.0.0.1:5000/generate-response', [
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => fopen($file->getPathname(), 'r'),
                        'filename' => $file->getClientOriginalName(),
                    ],
                    [
                        'name' => 'response_type',
                        'contents' => '0', // Or '0' based on what you want
                    ],
                ],
            ]);
            
    
            // Decode the JSON response from the Flask API
            $quizData = json_decode($response->getBody()->getContents(), true);
    
            // Extract the quiz questions from the response
            $questions = $quizData['questions'] ?? [];
    
            // Here, we'll assume that the topic can be inferred from the PDF or the AI's response.
            // If the AI sends back a topic, you can use it, otherwise fallback to 'Unknown' or another default.
            $topic = $quizData['topic'] ?? 'Unknown'; // Change this based on how your Python API works
    
            // Store the AI response in the database
            $aiResponse = ai_response::create([
                'user_id' => auth()->id(),  // Assuming you're using authentication
                'title' => $file->getClientOriginalName(),
                'response_type' => 0, // You can set the type accordingly
                'response_data' => json_encode($quizData),  // Store the full AI response
                'marks' => count($questions), // Example: You can assign marks based on number of questions
            ]);
    
            // Optionally, store the topic as well
            // You can adjust the logic if you want to store the topic separately
    
            // Return the quiz data to the frontend
            return response()->json([
                'quiz' => $questions,
                'topic' => $topic,
            ]);
    
        } catch (\Exception $e) {
            // Handle errors
            return response()->json(['error' => 'Failed to generate quiz: ' . $e->getMessage()], 500);
        }
    }
}
