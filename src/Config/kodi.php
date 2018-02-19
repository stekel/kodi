<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Kodi Configuration
    |--------------------------------------------------------------------------
    |
    | The following are custom configuration options for the stekel/kodi
    | package.
    |
    */
   
    'host' => env('KODI_HOST', '127.0.0.1'),
    'port' => env('KODI_PORT', '8080'),
    'username' => env('KODI_USERNAME', 'xbmc'),
    'password' => env('KODI_PASSWORD', 'xbmc'),
];
