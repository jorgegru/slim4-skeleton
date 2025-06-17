<?php

use DI\Container;
use Psr\Container\ContainerInterface;
use Slim\Factory\AppFactory;

// Create Container using PHP-DI
$container = new Container();

// Load application settings
$settings = require __DIR__ . '/settings.php';
$container->set('settings', $settings);

// Register dependencies first
$dependencies = require __DIR__ . '/dependencies.php';
foreach ($dependencies as $key => $dependency) {
    $container->set($key, $dependency);
}

// Set container to create App with on AppFactory
AppFactory::setContainer($container);

// Create App instance
$app = AppFactory::create();

// Initialize database connection
$container->get('db');

// Set base path if your application is not in the web root
// $app->setBasePath('/slim4');

// Register middleware
$middleware = require __DIR__ . '/middleware.php';
$middleware($app);

// Register routes
$routes = require __DIR__ . '/routes.php';
$routes($app);

// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware(
    $settings['displayErrorDetails'],
    true,
    true
);

// Set the error handler
$errorHandler = $container->get('errorHandler');
$errorMiddleware->setDefaultErrorHandler($errorHandler);

return $app;
