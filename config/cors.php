<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'storage/*'],

    'allowed_methods' => ['*'],

    // FRONTEND_URL accepte une ou plusieurs origines séparées par des virgules,
    // ex: FRONTEND_URL=https://mon-portfolio.vercel.app,http://localhost:5173
    // (sans slash final : une origine CORS est "schéma://domaine", rien d'autre).
    'allowed_origins' => array_values(array_filter(array_map(
        fn (string $origin) => rtrim(trim($origin), '/'),
        explode(',', (string) env('FRONTEND_URL', 'http://localhost:5173'))
    ))),

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,
];
