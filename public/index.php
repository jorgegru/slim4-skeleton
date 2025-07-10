<?php

/**
 * Slim 4 application entry point
 * 
 * This is the main entry point for the Slim 4 application.
 * It bootstraps the application and runs it.
 */

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Set the absolute path to the root directory
$rootPath = realpath(__DIR__ . '/..');

// Register the auto-loader
require $rootPath . '/vendor/autoload.php';

// Load environment variables from .env file if it exists
if (file_exists($rootPath . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable($rootPath);
    $dotenv->load();
}

// Set the default timezone
date_default_timezone_set('America/Sao_Paulo');

try {
    // Create the application
    $app = require $rootPath . '/src/app.php';

    // Add error handling for 404 Not Found
    $errorMiddleware = $app->addErrorMiddleware(
        $_ENV['APP_DEBUG'] === 'true',
        true,
        true
    );

    // Custom error handler
    $errorHandler = $errorMiddleware->getDefaultErrorHandler();
    $errorHandler->forceContentType('application/json');

    // Set the base path if needed
    $app->setBasePath('');

    // Log the request path for debugging
    $app->add(function (Request $request, $handler) {
        $response = $handler->handle($request);
        $path = $request->getUri()->getPath();
        $method = $request->getMethod();
        error_log("Request: $method $path");
        return $response;
    });

    // Run the application
    $app->run();
} catch (\Throwable $e) {
    // Log the error
    error_log('Application Error: ' . $e->getMessage());
    error_log('Stack Trace: ' . $e->getTraceAsString());
    
    // Return a JSON response for API errors
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'error' => [
            'status' => 500,
            'message' => 'Internal Server Error',
            'details' => $_ENV['APP_DEBUG'] === 'true' ? $e->getMessage() : 'An error occurred'
        ]
    ]);
}
