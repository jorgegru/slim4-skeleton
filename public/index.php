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

// Set the Not Found Handler
$app->setBasePath('');

// Run the application
$app->run();
