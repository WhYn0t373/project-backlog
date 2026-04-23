<?php
/**
 * Prometheus configuration.
 *
 * This file defines the namespace and path used by the Prometheus client.
 * Adjust as needed for your deployment.
 *
 * @package Config
 */

return [
    /**
     * Namespace for all metrics. Helps prevent collision with other services.
     */
    'namespace' => env('PROMETHEUS_NAMESPACE', 'app'),

    /**
     * Path to expose metrics. Should match the route defined in routes/api.php.
     */
    'path' => env('PROMETHEUS_METRICS_PATH', '/metrics'),

    /**
     * Port on which the metrics endpoint will listen.
     * This is only used when running the app in a container that exposes this port.
     */
    'port' => env('PROMETHEUS_METRICS_PORT', 9112),
];