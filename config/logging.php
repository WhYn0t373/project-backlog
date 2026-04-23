<?php
/**
 * Logging configuration.
 *
 * Adds a Loki channel using the custom LokiHandler.
 *
 * @package Config
 */

use App\Logging\LokiHandler;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name can be any of the channels defined in
    | the "channels" configuration below.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the "stack" log channel to log on multiple
    | channels at once.
    |
    | Available drivers: "single", "daily", "slack", "paperclip",
    |                    "syslog", "errorlog", "stack"
    |
    */

    'channels' => [

        'stack' => [
            'driver' => 'stack',
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
        ],

        // Loki channel
        'loki' => [
            'driver' => 'monolog',
            'handler' => LokiHandler::class,
            'handler_with' => [
                'endpoint' => env('LOKI_ENDPOINT', 'http://loki:3100/api/prom/push'),
                'labels'   => '{job="app",instance="' . env('APP_NAME', 'app') . '"}',
                'level'    => env('LOG_LEVEL', 'debug'),
            ],
            'level' => env('LOG_LEVEL', 'debug'),
            'formatter' => env('LOG_FORMATTER', \Monolog\Formatter\LineFormatter::class),
        ],

    ],

];