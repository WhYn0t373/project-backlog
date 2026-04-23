<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * API controller for order endpoints.
 *
 * @package App\Http\Controllers\Api
 */
class OrderController extends Controller
{
    /**
     * Display a paginated list of orders.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $page = request()->query('page', 1);
            $perPage = 1000; // Match chunk size

            // Cache key includes page number to avoid cache collisions
            $cacheKey = "orders_page_{$page}";

            $orders = Cache::remember($cacheKey, 900, function () use ($page, $perPage) {
                // Use Laravel's built-in pagination to get correct metadata
                $paginator = Order::paginate($perPage, ['*'], 'page', $page);
                return $paginator;
            });

            return response()->json([
                'data' => $orders->items(),
                'meta' => [
                    'current_page' => $orders->currentPage(),
                    'last_page' => $orders->lastPage(),
                    'per_page' => $orders->perPage(),
                    'total' => $orders->total(),
                ],
                'cached' => true,
            ], Response::HTTP_OK);
        } catch (\Throwable $e) {
            Log::error('Error retrieving orders: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            return response()->json([
                'error' => 'Unable to retrieve orders.',
                'details' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}