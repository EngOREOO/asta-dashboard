<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class PartnerController extends Controller
{
    /**
     * Display a listing of the partners.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $partners = Partner::orderBy('sort_order')
            ->where('is_active', true)
            ->get();
            
        return response()->json([
            'data' => $partners->map(function ($partner) {
                return [
                    'id' => $partner->id,
                    'name' => $partner->name,
                    'image_url' => $partner->image_url,
                    'website' => $partner->website,
                    'description' => $partner->description,
                    'sort_order' => $partner->sort_order,
                ];
            })
        ]);
    }

    /**
     * Store a newly created partner in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('partners', 'public');
            $data['image'] = $path;
        }

        $partner = Partner::create($data);

        return response()->json([
            'message' => 'Partner created successfully',
            'data' => $partner
        ], 201);
    }

    /**
     * Display the specified partner.
     *
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Partner $partner)
    {
        return response()->json([
            'data' => [
                'id' => $partner->id,
                'name' => $partner->name,
                'image_url' => $partner->image_url,
                'website' => $partner->website,
                'description' => $partner->description,
                'is_active' => $partner->is_active,
                'sort_order' => $partner->sort_order,
                'created_at' => $partner->created_at,
                'updated_at' => $partner->updated_at,
            ]
        ]);
    }

    /**
     * Update the specified partner in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Partner $partner)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        // Handle image upload if a new image is provided
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($partner->image && Storage::disk('public')->exists($partner->image)) {
                Storage::disk('public')->delete($partner->image);
            }
            
            $path = $request->file('image')->store('partners', 'public');
            $data['image'] = $path;
        }

        $partner->update($data);

        return response()->json([
            'message' => 'Partner updated successfully',
            'data' => $partner->fresh()
        ]);
    }

    /**
     * Remove the specified partner from storage.
     *
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Partner $partner)
    {
        // Delete the image file if it exists
        if ($partner->image && Storage::disk('public')->exists($partner->image)) {
            Storage::disk('public')->delete($partner->image);
        }

        $partner->delete();

        return response()->json([
            'message' => 'Partner deleted successfully'
        ], 204);
    }
}
