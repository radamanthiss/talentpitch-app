<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\GptController;
use App\Http\Controllers\UserController;


Route::post('/token', [TokenController::class, 'createToken']);

Route::get('/users',[ UserController::class, 'index'])->middleware('auth:sanctum');

Route::get('/users/{id}', [UserController::class, 'show'])->middleware('auth:sanctum');
Route::post('/users', [UserController::class, 'store'])->middleware('auth:sanctum');
Route::put('/users/{id}', [UserController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->middleware('auth:sanctum');

Route::get('/challenges', [ChallengeController::class, 'index'])->middleware('auth:sanctum');
Route::get('/challenges/{id}', [ChallengeController::class, 'show'])->middleware('auth:sanctum');
Route::post('/challenges', [ChallengeController::class, 'store'])->middleware('auth:sanctum');
Route::put('/challenges/{id}', [ChallengeController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/challenges/{id}', [ChallengeController::class, 'destroy'])->middleware('auth:sanctum');

Route::get('/companies', [CompanyController::class, 'index'])->middleware('auth:sanctum');
Route::get('/companies/{id}', [CompanyController::class, 'show'])->middleware('auth:sanctum');
Route::post('/companies', [CompanyController::class, 'store'])->middleware('auth:sanctum');
Route::put('/companies/{id}', [CompanyController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/companies/{id}', [CompanyController::class, 'destroy'])->middleware('auth:sanctum');

Route::get('programs', [ProgramController::class, 'index'])->middleware('auth:sanctum');
Route::get('programs/{id}', [ProgramController::class, 'show'])->middleware('auth:sanctum');
Route::post('programs', [ProgramController::class, 'store'])->middleware('auth:sanctum');
Route::put('programs/{id}', [ProgramController::class, 'update'])->middleware('auth:sanctum');
Route::delete('programs/{id}', [ProgramController::class, 'destroy'])->middleware('auth:sanctum');

Route::get('programs-participant', [ProgramController::class, 'index'])->middleware('auth:sanctum');
Route::get('programs-participant/{id}', [ProgramController::class, 'show'])->middleware('auth:sanctum');
Route::post('programs-participant', [ProgramController::class, 'store'])->middleware('auth:sanctum');
Route::put('programs-participant/{id}', [ProgramController::class, 'update'])->middleware('auth:sanctum');
Route::delete('programs-participant/{id}', [ProgramController::class, 'destroy'])->middleware('auth:sanctum');

Route::post('process-gpt', [GptController::class, 'generateAndIngest']);

// Route::fallback(function(){
//     return response()->json(['message' => 'Resource not found.'], 404);
// });