<?php

namespace App\Handler;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Throwable;

class ErrorHandler
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
        $message = 'An internal server error occurred';
        
        $errorDetails = [];
        
        if ($this->displayErrorDetails || $displayErrorDetails) {
            $errorDetails = [
                'message' => $exception->getMessage(),
                'exception' => get_class($exception),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => explode("\n", $exception->getTraceAsString()),
            ];
            
            $message = $exception->getMessage();
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
            
            $this->logger->error('PHP Error', $error);
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
