<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS Options
    |--------------------------------------------------------------------------
    |
    | This configuration file allows you to control CORS (Cross-Origin
    | Resource Sharing) settings for your Laravel application. It is used
    | to specify which origins can make requests to your API.
    |
    */

    'supports_credentials' => true,  // Permitir enviar credenciales (como cookies o sesiones)

    /*
    |--------------------------------------------------------------------------
    | Allowed Origins
    |--------------------------------------------------------------------------
    |
    | The list of allowed origins to make requests to the application. You can
    | specify multiple origins or use a wildcard '*' to allow all origins.
    |
    | Example: ['http://localhost:8080']
    |
    */
    'allowed_origins' => [
        'http://localhost:8080',  // Cambia esto con el puerto de tu frontend Vue.js
    ],

    'allowed_origins_patterns' => [],

    /*
    |--------------------------------------------------------------------------
    | Allowed Headers
    |--------------------------------------------------------------------------
    |
    | The list of allowed headers that are sent with the request.
    |
    */
    'allowed_headers' => [
        'Content-Type',
        'X-Requested-With',
        'X-Custom-Header',  // Puedes agregar otros encabezados personalizados que necesites
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed Methods
    |--------------------------------------------------------------------------
    |
    | The list of allowed HTTP methods that can be used for the request.
    |
    */
    'allowed_methods' => [
        'GET',
        'POST',
        'PUT',
        'DELETE',
        'PATCH',
        'OPTIONS',
    ],

    'exposed_headers' => [],
    'max_age' => 0,
    'hosts' => [],

];
