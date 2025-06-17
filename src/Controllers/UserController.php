<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;

class UserController
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    // Exemplo: Listar todos os usuários
    public function index(Request $request, Response $response): Response
    {
        $this->logger->info('Listando todos os usuários');

        $users = User::all(['id', 'username', 'email', 'name', 'is_active', 'is_verified', 'created_at']);

        $response->getBody()->write(json_encode([
            'status' => 'success',
            'data' => $users
        ]));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
