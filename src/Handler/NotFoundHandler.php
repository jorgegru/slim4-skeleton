<?php

namespace App\Handler;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use Slim\Interfaces\ErrorHandlerInterface;

class NotFoundHandler implements ErrorHandlerInterface
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
        $statusCode = 404;
        $message = 'Not Found';

        $response = $this->responseFactory->createResponse($statusCode);
        $response->getBody()->write(json_encode([
            'error' => [
                'status' => $statusCode,
                'message' => $message,
                'path' => $request->getUri()->getPath(),
                'method' => $request->getMethod(),
            ]
        ]));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
    }
}
