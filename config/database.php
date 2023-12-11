<?php

return [
    'connection_type' => 'sqlite',

    'connections' => [
        'sqlite' => [
            'driver' => isset($_ENV['DB_DRIVER']) ? $_ENV['DB_DRIVER'] : 'sqlite',
            'database' => isset($_ENV['DB_DATABASE']) ? $_ENV['DB_DATABASE'] : __DIR__ . '/../database/app.sqlite',
            'prefix' => isset($_ENV['DB_PREFIX']) ? $_ENV['DB_PREFIX'] : '',
            'foreign_key_constraints' => isset($_ENV['DB_FOREIGN_KEY_CONSTRAINTS']) ? $_ENV['DB_FOREIGN_KEY_CONSTRAINTS'] === 'true' : true,
        ]
    ]
];
