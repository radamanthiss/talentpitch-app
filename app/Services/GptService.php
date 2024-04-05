<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;

class GptService
{

  private $endpoint;
  private $apiKey;
  public function __construct()
  {
    $this->endpoint = env('GPT_ENDPOINT');
    $this->apiKey = env('GPT_API_KEY');
  }
  public function generateText($prompt)
  {
    error_log("endpoint: " . $this->endpoint);
    error_log("apiKey: " . $this->apiKey);
    try {
      $client = new Client();
      $response = $client->post($this->endpoint, [
        'headers' => [
          'Content-Type' => 'application/json',
          'Authorization' => 'Bearer ' . $this->apiKey,
        ],
        'json' => [
          'response_format' => ["type" => "json_object"],
          'messages' => [
            [
              'role' => 'user',
              'content' => $prompt
            ]
          ],
          'temperature' => 0.7,
          'top_p' => 1,
          'n' => 1,
          'stop' => ['\n'],
          'model' => 'gpt-3.5-turbo'
        ],
      ]);
      $result = json_decode($response->getBody(), true);
      // error_log(print_r($result['choices'][0]['message']['content'], true));
      // error_log(($result['choices'][0]['message']['content']));
      $content = $result['choices'][0]['message']['content'];
      $data = json_decode($content, true);
      return response()->json($data);
    } catch (\GuzzleHttp\Exception\ClientException $e) {
      return response()->json(['error' => 'Failed to communicate with the GPT API: ' . $e->getMessage()], $e->getCode() ?: 400);

      // if ($e->getResponse()->getStatusCode() == 429) {
      //   return response()->json(['error' => 'API rate limit exceeded. Please try again later.'], 429);
      // }
      // throw new \Exception('Failed to generate text: ' . $e->getMessage());
    }
  }
}
