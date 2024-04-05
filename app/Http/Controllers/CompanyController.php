<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Companies;
use App\Http\Resources\CompanyResource;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->query('page');
        $items = $request->query('items');
        $companies = Companies::paginate($items, ['*'], 'page', $page);
        return CompanyResource::collection($companies);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = Companies::create($request->all());
            return new CompanyResource($data);
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
            $data = Companies::findOrFail($id);
            return new CompanyResource($data);
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
            $data = Companies::findOrFail($id);
            if ($data) {
                $data->update($request->all());
                return new CompanyResource($data);
            }
        } catch (\Throwable $error) {
            return response()->json([
                'message' => 'Error: ' . $error->getMessage()
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $data = Companies::findOrFail($id);
            if ($data) {
                $data->delete();
                return response()->json([
                    'message' => 'Data deleted successfully'
                ], 200);
            }
        } catch (\Throwable $error) {
            return response()->json([
                'message' => 'Error: ' . $error->getMessage()
            ], 400);
        }
    }
}
