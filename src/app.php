<?php

use DI\Container;
use Psr\Container\ContainerInterface;
use Slim\Factory\AppFactory;
use Slim\Exception\HttpNotFoundException;

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

// Register middleware first
$middleware = require __DIR__ . '/middleware.php';
$middleware($app);

// Then register routes
$routes = require __DIR__ . '/routes.php';
$routes($app);

// Add Error Middleware (should be added after routes)
$errorMiddleware = $app->addErrorMiddleware(
    $settings['displayErrorDetails'] ?? false,
    true,
    true
);

// Set the error handler
$errorHandler = $container->get('errorHandler');
$errorMiddleware->setDefaultErrorHandler($errorHandler);

// Add Not Found handler
$errorMiddleware->setErrorHandler(
    HttpNotFoundException::class,
    function (Psr\Http\Message\ServerRequestInterface $request, \Throwable $exception, bool $displayErrorDetails) {
        $response = new \Slim\Psr7\Response();
        $data = [
            'error' => [
                'status' => 404,
                'message' => 'Not found.',
                'details' => [
                    'message' => 'The requested resource could not be found.',
                    'path' => $request->getUri()->getPath()
                ]
            ]
        ];
        
        $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
        return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
    }
);

return $app;
