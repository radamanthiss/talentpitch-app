<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ProgramResource;
use App\Models\Programs;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->query('page');
        $items = $request->query('items');
        $programs = Programs::paginate($items, ['*'], 'page', $page);
        return ProgramResource::collection($programs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = Programs::create($request->all());
            return new ProgramResource($data);
        } catch (\Throwable $error) {
            return response()->json([
                'message' => 'Error: ' . $error->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $data = Programs::find($id);
            if ($data) {
                return new ProgramResource($data);
            }
            return response()->json([
                'message' => 'Program not found'
            ], 404);
        } catch (\Throwable $error) {
            return response()->json([
                'message' => 'Error: ' . $error->getMessage()
            ], 400);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $program = Programs::find($id);
            if ($program) {
                $program->update($request->all());
                return new ProgramResource($program);
            }
            return response()->json([
                'message' => 'Program not found'
            ], 404);
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
            $program = Programs::find($id);
            if ($program) {
                $program->delete();
                return response()->json([
                    'message' => 'Program deleted'
                ], 200);
            }
            return response()->json([
                'message' => 'Program not found'
            ], 404);
        } catch (\Throwable $error) {
            return response()->json([
                'message' => 'Error: ' . $error->getMessage()
            ], 400);
        }
    }
}
