<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use App\Http\Requests\StoreFeatureRequest;
use App\Http\Requests\UpdateFeatureRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

/**
 * Controller for handling Feature CRUD operations.
 *
 * All methods are protected by authorization policies.
 */
class FeatureController extends Controller
{
    /**
     * Display a paginated listing of features.
     */
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Feature::class);

        $features = Feature::paginate(10);

        return response()->json($features);
    }

    /**
     * Store a newly created feature.
     */
    public function store(StoreFeatureRequest $request): JsonResponse
    {
        $this->authorize('create', Feature::class);

        $feature = Feature::create($request->validated());

        Log::info('Feature created', ['feature_id' => $feature->id, 'user_id' => Auth::id()]);

        return response()->json($feature, 201);
    }

    /**
     * Display the specified feature.
     */
    public function show(Feature $feature): JsonResponse
    {
        $this->authorize('view', $feature);

        return response()->json($feature);
    }

    /**
     * Update the specified feature.
     */
    public function update(UpdateFeatureRequest $request, Feature $feature): JsonResponse
    {
        $this->authorize('update', $feature);

        $feature->update($request->validated());

        Log::info('Feature updated', ['feature_id' => $feature->id, 'user_id' => Auth::id()]);

        return response()->json($feature);
    }

    /**
     * Remove the specified feature.
     */
    public function destroy(Feature $feature): JsonResponse
    {
        $this->authorize('delete', $feature);

        $feature->delete();

        Log::info('Feature deleted', ['feature_id' => $feature->id, 'user_id' => Auth::id()]);

        return response()->json(['message' => 'Feature deleted successfully.']);
    }
}