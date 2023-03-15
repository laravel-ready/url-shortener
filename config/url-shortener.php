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
];
