<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return function (\Slim\App $app) {
    // Home page route
    $app->get('/', function (Request $request, Response $response, $args) {
        $response->getBody()->write(
            '<h1>Welcome to Slim 4</h1>\n' .
            '<p>Your application is running successfully!</p>\n' .
            '<p><a href="/hello/world">Example route</a></p>'
        );
        return $response;
    });

    // Example route with parameter
    $app->get('/hello/{name}', function (Request $request, Response $response, $args) {
        $name = $args['name'];
        $response->getBody()->write("Hello, $name!");
        return $response;
    });

    // API example route
    $app->get('/api/status', function (Request $request, Response $response, $args) {
        $data = [
            'status' => 'success',
            'message' => 'API is running',
            'timestamp' => time()
        ];
        
        $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    });

        // Example route that uses the database
    $app->get('/db-test', function (Request $request, Response $response, $args) {
        try {
            $db = $this->get('db');
            $stmt = $db->query('SELECT 1');
            $result = $stmt->fetch();
            
            $response->getBody()->write(json_encode([
                'status' => 'success',
                'database' => 'Connected successfully',
                'result' => $result
            ], JSON_PRETTY_PRINT));
            
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
                
        } catch (\PDOException $e) {
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => 'Database connection failed',
                'error' => $e->getMessage()
            ], JSON_PRETTY_PRINT));
            
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    });
    
    // API: Listar usuários (usando UserController)
    $app->group('/api', function (\Slim\Routing\RouteCollectorProxy $group) use ($app) {
        $group->get('/users', function (Request $request, Response $response, $args) use ($app) {
            $container = $app->getContainer();
            $logger = $container->get(\Psr\Log\LoggerInterface::class);
            $controller = new \App\Controllers\UserController($logger);
            return $controller->index($request, $response);
        });
    });
};
