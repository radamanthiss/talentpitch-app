<?php

namespace App\Http\Controllers;

use App\Services\GptService;
use App\Services\DataIngestionService;
use Illuminate\Http\Request;

class GptController extends Controller


{
    protected $gptService;
    protected $ingestData;


    public function __construct(GptService $gptService, DataIngestionService $ingestData)
    {
        $this->gptService = $gptService;
        $this->ingestData = $ingestData;
    }


    public function generateAndIngest(Request $request)
    {

        $validated = $request->validate([
            'type' => 'required|string|in:users,companies,challenges,programs',
            'prompt' => 'required|string'
        ]);
        $generatedContent = $this->gptService->generateText($validated['prompt']);
        $jsonString = $generatedContent->getContent();

        $cleanedJsonString = str_replace(["\n", "\r", "\t"], "", $jsonString);
        $dataArray = json_decode($cleanedJsonString, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            error_log('JSON decoded successfully');
        } else {
            error_log('Failed to decode JSON: ' . json_last_error_msg());
        }
    
        // error_log('Type of dataArray: ' . gettype($dataArray));
        // error_log('Content of dataArray: ' . print_r($dataArray, true));


        if (!is_array($dataArray)) {
            error_log("Failed to decode JSON or JSON did not decode to an array. Actual content: " . print_r($jsonString, true));
            return; // Or handle the error as appropriate
        }
        error_log(print_r(array_keys($dataArray), true));

        // Assuming you've decoded JSON to $dataArray
        if (!isset($dataArray['users'])) {
            error_log('entro if del error');
            $dataArray = ['users' => is_array($dataArray) ? $dataArray : []];
        } else {
            // Log the actual structure for diagnostics
            foreach ($dataArray["users"] as $userData) {
                $this->ingestData->ingestData($validated['type'] , $userData);
            }
        }


        return response()->json(['message' => 'Data ingested successfully.']);
    }
}
