<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

return function (\Slim\App $app) {
    // Add custom headers middleware
    $app->add(function (Request $request, RequestHandler $handler) {
        $response = $handler->handle($request);
        return $response
            ->withHeader('X-Application-Name', 'Slim 4 App')
            ->withHeader('X-Application-Version', '1.0.0')
            ->withHeader('Content-Type', 'application/json');
    });

    // Parse JSON, Form Data and XML
    $app->addBodyParsingMiddleware();

    // Add Routing Middleware (should be after body parsing)
    $app->addRoutingMiddleware();

    // Add the built-in middleware for handling trailing slashes
    $app->add(function (Request $request, RequestHandler $handler) {
        $uri = $request->getUri();
        $path = $uri->getPath();
        
        if ($path != '/' && substr($path, -1) == '/') {
            // Redirect to non-trailing slash URL
            $uri = $uri->withPath(rtrim($path, '/'));
            
            if ($request->getMethod() == 'GET') {
                $response = new Response();
                return $response
                    ->withHeader('Location', (string)$uri)
                    ->withStatus(301);
            } else {
                $request = $request->withUri($uri);
            }
        }
        
        return $handler->handle($request);
    });
};
