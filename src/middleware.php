<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

return function (\Slim\App $app) {
    // Add Routing Middleware
    $app->addRoutingMiddleware();

    // Add Error Middleware (already added in app.php, but keeping here for reference)
    // $errorMiddleware = $app->addErrorMiddleware(true, true, true);

    // Example of adding a custom middleware
    $app->add(function (Request $request, RequestHandler $handler) {
        // Example: Add a custom header to all responses
        $response = $handler->handle($request);
        return $response
            ->withHeader('X-Application-Name', 'Slim 4 App')
            ->withHeader('X-Application-Version', '1.0.0');
    });

    // Parse JSON, Form Data and XML
    $app->addBodyParsingMiddleware();

    // Add the built-in middleware for handling the base path
    $app->add(new \Slim\Middleware\BodyParsingMiddleware());

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
