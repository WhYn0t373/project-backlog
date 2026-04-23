<?php
/**
 * Metrics Controller
 *
 * Handles the /metrics endpoint, exposing Prometheus metrics.
 *
 * @package App\Http\Controllers
 */

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Prometheus\RenderTextFormat;
use Prometheus\CollectorRegistry;

class MetricsController extends Controller
{
    /**
     * Return Prometheus metrics.
     *
     * @param  CollectorRegistry  $registry
     * @param  RenderTextFormat   $renderer
     * @return Response
     */
    public function index(CollectorRegistry $registry, RenderTextFormat $renderer): Response
    {
        try {
            $metrics = $renderer->render($registry->collect());
        } catch (\Throwable $e) {
            \Log::error('Failed to render Prometheus metrics: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            return response('Internal Server Error', 500);
        }

        return response($metrics, 200)
            ->header('Content-Type', $renderer::MIME_TYPE);
    }
}