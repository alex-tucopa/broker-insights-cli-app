<?php

return [
    'connection_type' => isset($_ENV['DB_CONNECTION_TYPE']) ? $_ENV['DB_CONNECTION_TYPE'] : 'sqlite',

    'connections' => [
        'sqlite' => [
            'driver' => isset($_ENV['DB_DRIVER']) ? $_ENV['DB_DRIVER'] : 'sqlite',
            'database' => isset($_ENV['DB_DATABASE']) ? $_ENV['DB_DATABASE'] : __DIR__ . '/../database/app.sqlite',
            'prefix' => isset($_ENV['DB_PREFIX']) ? $_ENV['DB_PREFIX'] : '',
            'foreign_key_constraints' => isset($_ENV['DB_FOREIGN_KEY_CONSTRAINTS']) ? $_ENV['DB_FOREIGN_KEY_CONSTRAINTS'] === 'true' : true,
        ],
        'mysql' => [
            'driver' => isset($_ENV['DB_DRIVER']) ? $_ENV['DB_DRIVER'] : 'mysql',
            'host' => isset($_ENV['DB_HOST']) ? $_ENV['DB_HOST'] : 'insights',
            'database' => isset($_ENV['DB_DATABASE']) ? $_ENV['DB_DATABASE'] : 'insights',
            'username' => isset($_ENV['DB_USERNAME']) ? $_ENV['DB_USERNAME'] : 'insights',
            'password' => isset($_ENV['MYSQL_PASSWORD']) ? $_ENV['MYSQL_PASSWORD'] : 'insights',
        ]
    ]
];
