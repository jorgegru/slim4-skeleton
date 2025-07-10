<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

return function (\Slim\App $app) {
    // Home page route
    $app->get('/', function (Request $request, Response $response, $args) {
        $data = [
            'name' => 'Slim 4 Application',
            'version' => '1.0.0',
            'status' => 'running',
            'timestamp' => time(),
            'endpoints' => [
                'GET /' => 'API status',
                'GET /api/status' => 'API status',
                'GET /api/users' => 'List all users',
                'GET /api/users/{id}' => 'Get user by ID',
                'GET /test' => 'Test route'
            ]
        ];
        
        $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    });

    // Status endpoint
    $app->get('/api/status', function (Request $request, Response $response) {
        $data = [
            'status' => 'success',
            'message' => 'API is running',
            'timestamp' => time()
        ];
        
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // Test route
    $app->get('/test', function (Request $request, Response $response) {
        $data = [
            'status' => 'success',
            'message' => 'Test route is working',
            'timestamp' => time()
        ];
        
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    });
    
    // API Group
    $app->group('/api', function (RouteCollectorProxy $group) {
        // Get all users
        $group->get('/users', 'App\\Controllers\\UserController:index');

        // Get user by ID
        $group->get('/users/{id:[0-9]+}', 'App\\Controllers\\UserController:show');
    });
};
