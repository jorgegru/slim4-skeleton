<?php

return [
    'displayErrorDetails' => true, // Set to false in production
    'determineRouteBeforeAppMiddleware' => false,
    
    // App settings
    'app' => [
        'name' => getenv('APP_NAME') ?: 'Slim 4 App',
        'env' => getenv('APP_ENV') ?: 'development',
        'debug' => (bool)(getenv('APP_DEBUG') ?: true),
        'url' => getenv('APP_URL') ?: 'http://localhost:8080',
        'timezone' => 'America/Sao_Paulo',
        'locale' => 'pt_BR',
        'fallback_locale' => 'pt_BR',
        'key' => getenv('APP_KEY') ?: 'base64:'.base64_encode(random_bytes(32)),
    ],
    
    // Database settings for Eloquent
    'db' => [
        'driver' => 'mysql',
        'host' => getenv('DB_HOST') ?: 'mariadb',
        'database' => getenv('DB_NAME') ?: 'slimdb',
        'username' => getenv('DB_USER') ?: 'slim',
        'password' => getenv('DB_PASS') ?: 'slim123',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'strict' => true,
        'engine' => null,
        'options' => [
            // Turn off persistent connections
            PDO::ATTR_PERSISTENT => false,
            // Enable exceptions
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            // Emulate prepared statements
            PDO::ATTR_EMULATE_PREPARES => true,
            // Set default fetch mode to array
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // Set character set
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci',
            // Enable buffered query
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
            // Set timeout
            PDO::ATTR_TIMEOUT => 30,
        ]
    ],
    
    // Renderer settings for Plates
    'renderer' => [
        'template_path' => __DIR__ . '/../templates',
        'cache_path' => getenv('VIEW_CACHE') ?: (__DIR__ . '/../var/cache/views'),
        'file_extension' => 'php',
        'folders' => [
            // You can add folder aliases here
            // 'emails' => 'templates/emails',
            // 'layouts' => 'templates/layouts',
        ]
    ],
    
    // Monolog settings
    'logger' => [
        'name' => 'slim-app',
        'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
        'level' => \Monolog\Logger::DEBUG,
    ],
    
    // Error handler settings
    'error' => [
        'display_error_details' => true,
        'log_errors' => true,
        'log_error_details' => true,
    ],
    
    // Session settings
    'session' => [
        'name' => 'slim4_session',
        'autorefresh' => true,
        'lifetime' => '1 hour',
    ],
    
    // Application settings
    'app' => [
        'name' => 'Slim 4 Application',
        'version' => '1.0.0',
        'environment' => getenv('APP_ENV') ?: 'development',
        'debug' => getenv('APP_DEBUG') !== 'false',
    ],
];
