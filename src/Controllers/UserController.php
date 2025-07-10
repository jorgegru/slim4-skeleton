<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Psr\Container\ContainerInterface;

class UserController
{
    private LoggerInterface $logger;
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->logger = $container->get(LoggerInterface::class);
    }

    /**
     * Lista todos os usuários
     */
    public function index(Request $request, Response $response): Response
    {
        $this->logger->info('Listando todos os usuários');

        try {
            $users = User::all(['id', 'username', 'email', 'name', 'is_active', 'is_verified', 'created_at']);
            
            $response->getBody()->write(json_encode([
                'status' => 'success',
                'data' => $users
            ]));
            
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $this->logger->error('Erro ao listar usuários: ' . $e->getMessage());
            
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => 'Erro ao processar a requisição'
            ]));
            
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }

    /**
     * Mostra um usuário específico
     */
    public function show(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);
        $this->logger->info('Buscando usuário', ['id' => $id]);

        try {
            $user = User::find($id);

            if (!$user) {
                $response->getBody()->write(json_encode([
                    'status' => 'error',
                    'message' => 'Usuário não encontrado'
                ]));
                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(404);
            }

            $response->getBody()->write(json_encode([
                'status' => 'success',
                'data' => $user
            ]));
            
            return $response->withHeader('Content-Type', 'application/json');
            
        } catch (\Exception $e) {
            $this->logger->error('Erro ao buscar usuário: ' . $e->getMessage(), [
                'id' => $id,
                'exception' => $e
            ]);
            
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => 'Erro ao processar a requisição'
            ]));
            
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }
}
