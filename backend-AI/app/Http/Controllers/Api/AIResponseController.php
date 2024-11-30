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
        // Validate the file upload
        try {
            $request->validate([
                'pdf' => 'required|file|mimes:pdf',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation failed', 'details' => $e->errors()], 422);
        }
    
        // Get the uploaded file
        $file = $request->file('pdf');
        
        // Send the file to Flask API
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
                        'contents' => '0',
                    ],
                ],
            ]);
            
            // Log the Flask API response
            Log::info('Flask API Response:', [
                'status' => $response->getStatusCode(),
                'body' => $response->getBody()->getContents(),
            ]);
    
            // Decode the response
            $responseData = json_decode($response->getBody()->getContents(), true);
    
            if (isset($responseData['error'])) {
                Log::error('Flask API Error:', $responseData);
                return response()->json(['error' => 'Flask API Error: ' . $responseData['error']], 500);
            }
    
            // Process the quiz data
            $quiz = $responseData['questions'] ?? [];
    
            // Return the quiz data without the topic
            return response()->json([
                'quiz' => $quiz,
            ]);
    
        } catch (\Exception $e) {
            Log::error('Error in Flask API Request:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to generate quiz: ' . $e->getMessage()], 500);
        }
    }
    
    
    

    public function destroy(ai_response $response)
    {

            $response->delete();
            return response()->json(['message' => 'Response deleted successfully']);
    }
}
