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
            ]);
        $file = $request->file('pdf');
    

        $client = new Client();
       
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
    
            $responseBody = $response->getBody()->getContents();
            $responseData = json_decode($responseBody, true);
                return response()->json([
                    'quiz' => $responseData,
                ]);
        
    
   
    }
    
    
    
    
    

    public function destroy(ai_response $response)
    {

            $response->delete();
            return response()->json(['message' => 'Response deleted successfully']);
    }
}
