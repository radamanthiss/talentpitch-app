<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ProgramParticipantResource;
use App\Models\ProgramParticipants;
use Illuminate\Support\Facades\Validator;

class ProgramParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->query('page');
        $items = $request->query('items');
        $programParticipants = ProgramParticipants::paginate($items, ['*'], 'page', $page);
        return ProgramParticipantResource::collection($programParticipants);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'program_id' => 'required|integer|exists:programs,id',
                'entity_id' => 'required|integer',
                'entity_type' => 'required|string|in:user,challenge,company',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }
            $participant = new ProgramParticipants();

            $participant->program_id = $request->program_id;
            $participant->entity_id = $request->entity_id;
            $participant->entity_type = $request->entity_type;
            $participant->save();
            return new ProgramParticipantResource($participant);
        } catch (\Throwable $error) {
            return response()->json([
                'message' => 'Error: ' . $error->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $participant = ProgramParticipants::findOrFail($id);
            if ($participant) {
                return new ProgramParticipantResource($participant);
            } else {
                return response()->json([
                    'message' => 'Program participant not found'
                ], 404);
            }
        } catch (\Throwable $error) {
            return response()->json([
                'message' => 'Error: ' . $error->getMessage()
            ], 400);
        }
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        try {
            $participant = ProgramParticipants::findOrFail($id);
            $validator = Validator::make($request->all(), [
                'program_id' => 'required|integer|exists:programs,id',
                'entity_id' => 'required|integer',
                'entity_type' => 'required|string|in:user,challenge,company',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }
            $participant->update([
                'program_id' => $request->program_id,
                'entity_id' => $request->entity_id,
                'entity_type' => $request->entity_type
            ]);
            return new ProgramParticipantResource($participant);
        } catch (\Throwable $error) {
            return response()->json([
                'message' => 'Error: ' . $error->getMessage()
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $participant = ProgramParticipants::findOrFail($id);
            $participant->delete();
            return response()->json([
                'message' => 'Program participant deleted successfully'
            ], 200);
        } catch (\Throwable $error) {
            return response()->json([
                'message' => 'Error: ' . $error->getMessage()
            ], 400);
        }
    }
}
