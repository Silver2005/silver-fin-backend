<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    */

    // Ajout des routes spécifiques pour Sanctum et l'API
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'login', 'register', 'logout'],

    'allowed_methods' => ['*'],

    /* | On remplace le '*' par les domaines réels pour plus de sécurité.
    | Note : Ne pas mettre de '/' à la fin des URLs.
    */
    'allowed_origins' => [
        'https://silver-fin-frontend1.onrender.com', // Ton site frontend
        'https://silver-fin-backend-3.onrender.com', // Ton site backend
        'http://localhost:5173',                    // Pour le développement local (Vite)
        'http://127.0.0.1:5173',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // Très important pour Sanctum ou si tu utilises des cookies/sessions
    'supports_credentials' => true,

];