<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeatureRequest;
use App\Models\Feature;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 * RESTful controller for managing features.
 */
class FeatureController extends Controller
{
    /**
     * Display a listing of the features.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $features = Feature::all();
            return response()->json(['data' => $features], 200);
        } catch (\Throwable $e) {
            Log::error('Failed to list features: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'Unable to retrieve features.'], 500);
        }
    }

    /**
     * Store a newly created feature.
     *
     * @param  FeatureRequest  $request
     * @return JsonResponse
     */
    public function store(FeatureRequest $request): JsonResponse
    {
        try {
            $feature = Feature::create($request->validated());
            return response()->json(['data' => $feature], 201);
        } catch (\Throwable $e) {
            Log::error('Failed to create feature: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'Unable to create feature.'], 500);
        }
    }

    /**
     * Display the specified feature.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $feature = Feature::findOrFail($id);
            return response()->json(['data' => $feature], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("Feature with id {$id} not found.");
            return response()->json(['error' => 'Feature not found.'], 404);
        } catch (\Throwable $e) {
            Log::error('Failed to retrieve feature: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'Unable to retrieve feature.'], 500);
        }
    }

    /**
     * Update the specified feature.
     *
     * @param  FeatureRequest  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(FeatureRequest $request, int $id): JsonResponse
    {
        try {
            $feature = Feature::findOrFail($id);
            $feature->update($request->validated());
            return response()->json(['data' => $feature], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("Feature with id {$id} not found for update.");
            return response()->json(['error' => 'Feature not found.'], 404);
        } catch (\Throwable $e) {
            Log::error('Failed to update feature: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'Unable to update feature.'], 500);
        }
    }

    /**
     * Remove the specified feature.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $feature = Feature::findOrFail($id);
            $feature->delete();
            return response()->json(['message' => 'Feature deleted successfully.'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("Feature with id {$id} not found for deletion.");
            return response()->json(['error' => 'Feature not found.'], 404);
        } catch (\Throwable $e) {
            Log::error('Failed to delete feature: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'Unable to delete feature.'], 500);
        }
    }
}