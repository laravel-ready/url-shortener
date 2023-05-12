<?php

return [
    /**
     * Table name to store short url and long url
     * Must be unique and singular
     * 
     * Default: 'short_url'
     */
    'table_name' => 'short_url',

    /**
     * Characters used to generate short url
     * 
     * Default: '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
     */
    'characters' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',

    /**
     * Length of short url
     * 
     * Default: 3
     */
    'min_length' => 3,

    /**
     * Max length of short url
     * 
     * Default: 5
     */
    'max_length' => 5,

    /**
     * Route prefix for redirections
     * Example: http://example.com/r/{short_code}
     * 
     * If you want to handle redirections by yourself, you can set this to null
     * 
     * Default: 'r'
     */
    'redirect_route_prefix' => 'r',

    /**
     * Redirect controller for redirections
     * 
     * Default: \LaravelReady\UrlShortener\Http\Controllers\RedirectController::class
     */
    'redirect_controller' => \LaravelReady\UrlShortener\Http\Controllers\RedirectController::class,

    /**
     * Emoji related settings
     */
    'emoji' => [
        /**
         * Enable emoji short url
         * 
         * Default: true
         */
        'allow' => true,

        /**
         * Name of the database file
         */
        'database' => 'emojipadia-v1.0.db',

        /**
         * Minimum unicode version
         * 
         * @see https://unicode.org/versions/Unicode15.0.0/
         */
        'min_unicode_version' => 1.0,

        /**
         * Maximum unicode version
         * 
         * @see https://unicode.org/versions/Unicode15.0.0/
         */
        'max_unicode_version' => 15.0,

        /**
         * Minimum emoji version
         * 
         * @see https://unicode.org/emoji/charts/emoji-versions.html
         */
        'min_emoji_version' => 1.0,

        /**
         * Maximum emoji version
         * 
         * @see https://unicode.org/emoji/charts/emoji-versions.html
         */
        'max_emoji_version' => 15.0,

        /**
         * Base route for emoji
         * 
         * We will use this route to list emojis
         * 
         * Default: 'emojis'
         */
        'base_route' => 'emojis',
    ],

    /**
     * Ready to use basic API
     */
    'api' => [
        /**
         * Enable URL shortener API
         * 
         * Default: true
         */
        'enable' => true,

        /**
         * Route prefix for API
         * 
         * Default: 'api/v1/url-shortener'
         */
        'route' => 'api/v1/url-shortener',

        /**
         * Route middlewares
         * 
         * Default: ['api', 'auth:api']
         */
        'middleware' => ['api', 'auth:api'],
    ]
];
