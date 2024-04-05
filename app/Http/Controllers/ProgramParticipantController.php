<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ProgramParticipantResource;
use App\Models\ProgramParticipants;
use Illuminate\Support\Facades\Validator;
use App\Models\Programs;
use App\Models\User;
use App\Models\Challenges;
use App\Models\Companies;
use InvalidArgumentException;

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

    public function addParticipantToProgram(Request $request)
    {
        // Validate the request data
        $request->validate([
            'program_id' => 'required|integer',
            'entity_id' => 'required|integer',
            'entity_type' => 'required|string', // Expected values: 'user', 'challenge', 'company'
        ]);

        $program = Programs::findOrFail($request->program_id);

        // Determine the participant model based on the participant_type
        $participantModel = $this->getParticipantModel($request->participant_type);

        // Find the participant model instance
        $participant = $participantModel::findOrFail($request->participant_id);

        // Create a new ProgramParticipant entry
        $programParticipant = new ProgramParticipants([
            'entity_id' => $participant->id,
            'entity_type' => get_class($participant),
        ]);

        // Associate with the program
        $program->participants()->save($programParticipant);

        return response()->json(['message' => 'Participant added successfully']);
    }

    /**
     * Determine the participant model based on the provided type.
     *
     * @param string $type
     * @return string
     */
    protected function getParticipantModel($type)
    {
        switch ($type) {
            case 'user':
                return User::class;
            case 'challenge':
                return Challenges::class;
            case 'company':
                return Companies::class;
            default:
                throw new InvalidArgumentException("Invalid participant type provided.");
        }
    }
}
