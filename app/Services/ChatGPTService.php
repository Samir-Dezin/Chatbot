<?php

namespace App\Services;

use App\Models\ApiLog;

class ChatGPTService
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = env("RAPIDAPI_URL");  
        $this->apiKey = env("RAPIDAPI_KEY","de400cff03mshd636b6f46cca699p15cec1jsn675599bc1f0c");
    }

    public function getResponse($query)
    {

        // ################ API LOG #############

             $log =new ApiLog();
             $log->endpoint = $this->apiUrl;
             $log->request_payload=json_encode(['query'=>$query, 'sysMsg'=>'You are a friendly      chatbot']);
             $log->ip_address=$_SERVER['REMOTE_ADDR'];
             $log->save();


        //################ CURL REQUEST ###########

        $curl = curl_init();

        curl_setopt_array($curl, [
             CURLOPT_URL => $this->apiUrl,
             CURLOPT_RETURNTRANSFER => true,
             CURLOPT_ENCODING => "",
             CURLOPT_MAXREDIRS => 10,
             CURLOPT_TIMEOUT => 30,
             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
             CURLOPT_CUSTOMREQUEST => "POST",
             CURLOPT_POSTFIELDS => json_encode([
                'query' => $query,
                'sysMsg' => 'You are a friendly Chatbot.'
             ]),
             CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "x-rapidapi-host: infinite-gpt.p.rapidapi.com",
                "x-rapidapi-key: $this->apiKey"
            ],
        ]);

            $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);

        //############### RESPONSE VALIDATION ############

        if ($err) {

             $log->success=false;
             $log->error_message="cURL Error: $err";
             $log->save();

             return ['error' => "cURL Error: $err"];
        } else {
             $log->success=true;
             $log->response_payload=$response;
             $log->save();


             return json_decode($response, true);

             
        }
    }
    
}
