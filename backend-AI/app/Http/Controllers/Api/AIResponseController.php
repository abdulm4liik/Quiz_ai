<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Log;
use App\Models\ai_response; 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ai_resource;
use GuzzleHttp\Client;

class AIResponseController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $aiResponses = ai_response::query()
            ->when($search, function ($query, $search) {
                $query->where('title', 'LIKE', "%{$search}%");
            })
            ->where('user_id', auth()->id()) 
            ->orderBy('id', 'desc') 
            ->paginate(6); 
    
        return response()->json([
            'data' => ai_resource::collection($aiResponses),
            'meta' => [
                'current_page' => $aiResponses->currentPage(),
                'total' => $aiResponses->total(),
                'last_page' => $aiResponses->lastPage(),
                'from' => $aiResponses->firstItem(),
                'to' => $aiResponses->lastItem(),
            ]
        ]);
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'pdf' => 'required|file|mimes:pdf',
            'response_type' => 'required|integer|in:0,1',
        ]);
    
        $file = $request->file('pdf');
        $response_type = $request->input('response_type');  
    
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
                        'contents' => $response_type,  
                    ],
                ],
            ]);
    
            $responseBody = $response->getBody()->getContents();
            $responseData = json_decode($responseBody, true);
    
            if ($response_type == 1 && isset($responseData['quiz'])) {
                $correctAnswers = array_column($responseData['quiz'], 'correct_answer');
                $marks = [
                    'correct_answers' => $correctAnswers,
                    'your_answers' => [],
                    'total' => null,
                ];
            } else {
                $marks = [];
            }
    
            $aiResponse = ai_response::create([
                'user_id' => auth()->id(),
                'title' => $file->getClientOriginalName(),
                'response_type' => $response_type,
                'response_data' => $responseData,
                'marks' => $marks,
            ]);
    
            return response()->json([
                'message' => 'AI response successfully stored.',
                'data' => $aiResponse,
            ]);
        
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to process the AI response.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function answer(Request $request)
    {
        // Receive the data from the frontend
        $quizId = $request->input('quiz_id');
        $answers = $request->input('answers');
        $totalScore = $request->input('total_score');
    
        // Check if the necessary data is provided
        if (!$quizId || !$answers || !isset($totalScore)) {
            return response()->json(['message' => 'Missing data: quiz_id, answers, or total_score'], 400);
        }
    
        // Find the AI response by quiz_id
        $response = ai_response::find($quizId);
    
        if (!$response) {
            return response()->json(['message' => 'AI response not found'], 404);
        }
    
        // Assuming we receive correct answers from frontend as well
        // The correct answers would typically be stored in a different field or table
        $correctAnswers = $request->input('correct_answers'); // Add correct_answers to the request
    
        // If correct_answers are missing from the request, return an error
        if (!$correctAnswers) {
            return response()->json(['message' => 'Correct answers are missing'], 400);
        }
    
        // Prepare the marks data to save
        $marksData = [
            'correct_answers' => $correctAnswers,
            'your_answers' => $answers,
            'total' => $totalScore
        ];
    
        // Update the response object with the marks data
        $response->marks = $marksData;
    
        // Save the response object
        try {
            $response->save();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update AI response'], 500);
        }
    
        // Return success response with the updated data
        return response()->json([
            'message' => 'Quiz answers successfully stored.',
            'data' => $response
        ]);
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    

    public function destroy(ai_response $response)
    {

            $response->delete();
            return response()->json(['message' => 'Response deleted successfully']);
    }
}
