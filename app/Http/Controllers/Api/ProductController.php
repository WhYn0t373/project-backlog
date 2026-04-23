<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * API controller for product endpoints.
 *
 * @package App\Http\Controllers\Api
 */
class ProductController extends Controller
{
    /**
     * Display a paginated list of products with eager-loaded orders.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            // Attempt to return cached result first
            $cachedKey = 'products_with_orders';
            $products = Cache::remember($cachedKey, 60 * 60, function () {
                $results = [];
                // Chunk to reduce memory footprint
                Product::with('orders')
                    ->chunk(500, function ($chunk) use (&$results) {
                        foreach ($chunk as $product) {
                            $results[] = $product;
                        }
                    });

                return $results;
            });

            return response()->json([
                'data' => $products,
                'cached' => true,
            ], Response::HTTP_OK);
        } catch (\Throwable $e) {
            Log::error('Error retrieving products: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            return response()->json([
                'error' => 'Unable to retrieve products.',
                'details' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}