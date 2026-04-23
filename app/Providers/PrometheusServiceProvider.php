<?php
/**
 * Prometheus Service Provider
 *
 * Registers the Prometheus client and exposes a singleton instance
 * that can be resolved via the container.
 *
 * @package App\Providers
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Prometheus\CollectorRegistry;
use Prometheus\Storage\Redis;
use Prometheus\RenderTextFormat;
use Prometheus\Storage\Adapter;
use Prometheus\Storage\InMemory;
use Prometheus\Counter;
use Prometheus\Gauge;
use Prometheus\Histogram;
use Prometheus\Summary;
use Prometheus\MetricFamilySamples;
use Prometheus\Metrics\Registry;

class PrometheusServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('prometheus', function ($app) {
            // Default to InMemory storage; override with Redis if needed
            $storage = $app->has('redis') ? new Redis($app['redis']->connection()->client()) : new InMemory();

            $registry = new CollectorRegistry($storage);

            // Example custom metric
            $registry->registerCounter(
                config('prometheus.namespace'),
                'app_requests_total',
                'Total number of HTTP requests received by the application',
                ['route', 'method']
            );

            return $registry;
        });

        // Expose the RenderTextFormat for the controller
        $this->app->singleton('prometheus.render', function () {
            return new RenderTextFormat();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // No boot logic required for now
    }
}