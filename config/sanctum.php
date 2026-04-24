<?php

return [

    /**
     * Sanctum stateful domains.
     *
     * These domains will receive stateful cookies and will be able to send CSRF tokens for authenticated requests. Adjust this list to
     * match your frontend domains when testing.
     */
    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', 'localhost')),

    /**
     * Token expiration time in minutes.
     *
     * Tokens will be automatically expired after this many minutes.
     * The value 60 means tokens are valid for one hour.
     */
    'expiration' => 60,

    /**
     * Middleware that should be applied to Sanctum routes.
     */
    'middleware' => ['web'],

    /**
     * Prefix used for Sanctum routes.
     */
    'prefix' => 'sanctum',
];