<?php

namespace App\Services;

use GuzzleHttp\Client;

class ChatGPTService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('CHATGPT_API_KEY');
    }

    public function askToChatGpt($prompt)
    {
        $response = $this->client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                "model" => "gpt-3.5-turbo",
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        return $data['choices'][0]['message']['content'] ?? 'No summary';
    }
}
