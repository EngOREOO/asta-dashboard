<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DegreeResource;
use App\Models\Degree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DegreeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Degree::with(['instructors:id,name,profile_photo_path'])
            ->withCount('courses')
            ->withAvg('courses', 'average_rating')
            ->withAvg('courses', 'price')
            ->orderBy('sort_order')
            ->where('is_active', true);

        // Filter by category if provided
        if ($request->has('category_id') && $request->category_id) {
            $query->whereHas('courses', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        $degrees = $query->get();

        return DegreeResource::collection($degrees);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'provider' => 'nullable|string|max:255',
            'level' => 'required|integer|unique:degrees,level',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $degree = Degree::create($validator->validated());

        return new DegreeResource($degree);
    }

    /**
     * Display the specified resource.
     */
    public function show(Degree $degree)
    {
        $degree->load(['instructors:id,name,profile_photo_path']);
        $degree->loadCount('courses');
        $degree->loadAvg('courses', 'average_rating');
        $degree->loadAvg('courses', 'price');

        return new DegreeResource($degree);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Degree $degree)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'name_ar' => 'sometimes|string|max:255',
            'provider' => 'nullable|string|max:255',
            'level' => 'sometimes|integer|unique:degrees,level,'.$degree->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $degree->update($validator->validated());

        return new DegreeResource($degree);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Degree $degree)
    {
        // Check if the degree is being used by any courses
        if ($degree->courses()->exists()) {
            return response()->json([
                'message' => 'Cannot delete degree as it is being used by one or more courses.',
            ], 422);
        }

        $degree->delete();

        return response()->json(['message' => 'Degree deleted successfully']);
    }
}
