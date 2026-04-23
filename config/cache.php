<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Cache Store
    |--------------------------------------------------------------------------
    |
    | This option controls the default cache connection that gets used while
    | using this caching library. The array of options is defined in
    | the `config/cache.php` file. This value can be overridden per
    | environment via the `CACHE_DRIVER` environment variable.
    |
    */

    'default' => env('CACHE_DRIVER', 'redis'),

    /*
    |--------------------------------------------------------------------------
    | Cache Stores
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the cache "stores" for your application.
    | Each driver has its own set of options. Some drivers are configured
    | in the `config/database.php` file for your convenience.
    |
    */

    'stores' => [

        'file' => [
            'driver' => 'file',
            'path' => storage_path('framework/cache/data'),
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ],

        // Add other cache stores here if needed
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Key Prefix
    |--------------------------------------------------------------------------
    |
    | When using remote cache stores such as Memcached or Redis, cache
    | keys are stored in the same namespace across all applications.
    | The prefix is used to prevent collisions. You may change this
    | prefix to anything you'd like.
    |
    */

    'prefix' => env('CACHE_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_') . '_cache'),

];