<?php

return [

    'supports_credentials' => true,  // Permitir credenciales (cookies o tokens)

    'allowed_origins' => [
        'http://localhost:5173',  // Cambia esto al dominio de tu frontend
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => [
        'Content-Type', 
        'X-Requested-With', 
        'Authorization',  // Importante: Permite este encabezado para autenticación
        'Accept',
    ],

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

