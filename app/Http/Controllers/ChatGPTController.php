<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ChatGPTService;

class ChatGPTController extends Controller
{
    protected $chatGPTService;

    public function __construct(ChatGPTService $chatGPTService)
    {
        $this->chatGPTService = $chatGPTService;
    }

    public function index(){
       return "hello world";
    }
    

    public function getResponse(Request $request)
    {
        // Extract query from request body
        $query = $request->input('query', 'hi'); // Default to 'hi' if no query is provided
        $response = $this->chatGPTService->getResponse($query);

        if (isset($response['error'])) {
            return response()->json(['error' => $response['error']], 500);
        }

        return response()->json(['response' => $response], 200);
    }
}
