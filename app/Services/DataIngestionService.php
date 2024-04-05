<?php

namespace App\Services;

use App\Models\User;
use App\Models\Companies;
use App\Models\Challenges;
use App\Models\Programs;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DataIngestionService
{
  public function ingestData($type, $data)
  {
    print_r($data); // See what the data looks like

    // if (!is_array($data)) {
    //   $data = ['content' => $data];
    // }

    // $validator = Validator::make($data, [
    //   'content' => 'required|string'
    // ]);

    // if ($validator->fails()) {
    //   throw new \Exception('Validation failed: ' . implode('; ', $validator->errors()->all()));
    // }
    switch ($type) {
      case 'users':
        $model = User::class;
        $validatorRules = User::validatorRules(); // You need to define this method in your model
        break;
      case 'companies':
        $model = Companies::class;
        break;
      case 'challenges':
        $model = Challenges::class;
        break;
      case 'programs':
        $model = Programs::class;
        break;
      default:
        throw new \Exception('Invalid data type provided.');
    }
    $validator = Validator::make($data, $validatorRules); // Validate the data before creating the model instance.
    if ($validator->fails()) {
      throw new \Exception('Validation failed: ' . implode('; ', $validator->errors()->all()));
    }
    // Use DB transaction for safety.
    DB::beginTransaction();
    try {
      $result = $model::create($data);
      DB::commit();
      return $result;
    } catch (\Exception $e) {
      DB::rollBack();
      throw $e; // Rethrow the exception after rolling back the transaction.
    }
  }
}
