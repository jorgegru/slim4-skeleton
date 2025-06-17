<?php

namespace App\Handler;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Interfaces\ErrorHandlerInterface;

class NotAllowedHandler implements ErrorHandlerInterface
{
    private ResponseFactoryInterface $responseFactory;

    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(
        Request $request,
        \Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ): ResponseInterface {
        $statusCode = 405;
        $message = 'Method not allowed';
        $allowedMethods = [];

        if ($exception instanceof HttpMethodNotAllowedException) {
            $allowedMethods = $exception->getAllowedMethods();
        }

        $response = $this->responseFactory->createResponse($statusCode);
        $response->getBody()->write(json_encode([
            'error' => [
                'status' => $statusCode,
                'message' => $message,
                'path' => $request->getUri()->getPath(),
                'method' => $request->getMethod(),
                'allowed_methods' => $allowedMethods,
            ]
        ]));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Allow', implode(', ', $allowedMethods))
            ->withStatus($statusCode);
    }
}
