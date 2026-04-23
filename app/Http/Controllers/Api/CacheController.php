<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CacheService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller for managing cache invalidation via API.
 *
 * @package App\Http\Controllers\Api
 */
class CacheController extends Controller
{
    /**
     * Invalidate product cache.
     *
     * @return JsonResponse
     */
    public function invalidateProducts(): JsonResponse
    {
        $success = CacheService::forget('products_with_orders');

        return response()->json([
            'message' => $success ? 'Products cache invalidated.' : 'Failed to invalidate products cache.',
        ], Response::HTTP_OK);
    }

    /**
     * Invalidate orders cache.
     *
     * @return JsonResponse
     */
    public function invalidateOrders(): JsonResponse
    {
        $success = CacheService::forget('orders_page_1');

        return response()->json([
            'message' => $success ? 'Orders cache invalidated.' : 'Failed to invalidate orders cache.',
        ], Response::HTTP_OK);
    }
}