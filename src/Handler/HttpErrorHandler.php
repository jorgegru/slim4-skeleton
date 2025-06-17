<?php

namespace App\Handler;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpException;
use Slim\Interfaces\ErrorHandlerInterface;
use Throwable;

class HttpErrorHandler implements ErrorHandlerInterface
{
    private ResponseFactoryInterface $responseFactory;
    private LoggerInterface $logger;
    private bool $displayErrorDetails;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        LoggerInterface $logger,
        bool $displayErrorDetails = false
    ) {
        $this->responseFactory = $responseFactory;
        $this->logger = $logger;
        $this->displayErrorDetails = $displayErrorDetails;
    }

    public function __invoke(
        Request $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ): ResponseInterface {
        $statusCode = 500;
        $message = 'Internal Server Error';
        $errorDetails = [];

        if ($exception instanceof HttpException) {
            $statusCode = $exception->getCode();
            $message = $exception->getMessage();
        }

        if ($this->displayErrorDetails || $displayErrorDetails) {
            $errorDetails = [
                'message' => $message,
                'exception' => get_class($exception),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => explode("\n", $exception->getTraceAsString()),
            ];
        }

        // Log the error
        if ($logErrors) {
            $error = [
                'message' => $message,
                'code' => $statusCode,
                'uri' => (string)$request->getUri(),
                'method' => $request->getMethod(),
            ];

            if ($logErrorDetails) {
                $error['details'] = $errorDetails;
            }

            $this->logger->error('HTTP Error', $error);
        }

        // Create response
        $response = $this->responseFactory->createResponse($statusCode);
        $response->getBody()->write(json_encode([
            'error' => [
                'status' => $statusCode,
                'message' => $message,
                'details' => $this->displayErrorDetails ? $errorDetails : null,
            ]
        ]));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
    }
}
