<?php

namespace App\Http\Controllers\Api;

use GuzzleHttp\Client;
use App\Models\ai_response; 
use Illuminate\Http\Request;
use App\Http\Resources\ai_resource;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\AIresponseRequest;

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
    

    public function store(AIresponseRequest $request)
    {
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

    public function answer(AIresponseRequest $request)
    {

        $aiResponse = ai_response::find($request->quiz_id);
    
        if ($aiResponse) {

            $aiResponse->marks = $request->marks;
            $aiResponse->save();  
            
            return response()->json(['message' => 'Quiz results updated successfully', 'data' => $aiResponse]);
        } else {
            return response()->json(['message' => 'Quiz ID not found'], 404);
        }
    }
    
    

    public function destroy(ai_response $response)
    {

            $response->delete();
            return response()->json(['message' => 'Response deleted successfully']);
    }
}
