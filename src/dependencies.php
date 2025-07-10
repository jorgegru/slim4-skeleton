<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Selective\BasePath\BasePathMiddleware;
use Slim\App;
use Slim\Factory\AppFactory;
use League\Plates\Engine as PlatesEngine;
use Symfony\Component\Translation\Loader\PhpFileLoader;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\ArrayLoader;

return [
    // Response Factory
    ResponseFactoryInterface::class => function () {
        return new \Slim\Psr7\Factory\ResponseFactory();
    },
    
    // Response Factory (alias para compatibilidade)
    'responseFactory' => function (ContainerInterface $container) {
        return $container->get(ResponseFactoryInterface::class);
    },

    // Application settings
    'settings' => function () {
        return require __DIR__ . '/settings.php';
    },

    // Eloquent ORM
    'db' => function (ContainerInterface $container) {
        $settings = $container->get('settings')['db'];
        
        $capsule = new Capsule;
        $capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => $settings['host'],
            'database'  => $settings['database'],
            'username'  => $settings['username'],
            'password'  => $settings['password'],
            'charset'   => $settings['charset'],
            'collation' => $settings['collation'],
            'prefix'    => $settings['prefix'] ?? '',
        ]);

        // Make this Capsule instance available globally via static methods... (optional)
        $capsule->setAsGlobal();
        
        // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
        $capsule->bootEloquent();
        
        return $capsule;
    },

    // Monolog logger
    Psr\Log\LoggerInterface::class => function (ContainerInterface $container) {
        $settings = $container->get('settings')['logger'];
        $logger = new Monolog\Logger($settings['name']);
        $logger->pushProcessor(new Monolog\Processor\UidProcessor());
        $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
        return $logger;
    },

    // Plates template engine
    PlatesEngine::class => function (ContainerInterface $container) {
        $settings = $container->get('settings')['renderer'];
        
        // Create new Plates instance
        $plates = new PlatesEngine($settings['template_path']);
        
        // Add any global data that should be available to all templates
        $plates->addData([
            'baseUrl' => $container->get('settings')['app']['baseUrl'] ?? '',
            'appName' => $container->get('settings')['app']['name'] ?? 'Slim 4 App',
        ]);
        
        return $plates;
    },

    // Error handler
    'errorHandler' => function (ContainerInterface $container) {
        $settings = $container->get('settings')['error'];
        return new \App\Handler\HttpErrorHandler(
            $container->get(ResponseFactoryInterface::class),
            $container->get(Psr\Log\LoggerInterface::class),
            $settings['display_error_details']
        );
    },

    // PHP Error handler
    'phpErrorHandler' => function (ContainerInterface $container) {
        $settings = $container->get('settings')['error'];
        return new \App\Handler\ErrorHandler(
            $container->get(ResponseFactoryInterface::class),
            $container->get(Psr\Log\LoggerInterface::class),
            $settings['display_error_details']
        );
    },

    // Not Found Handler
    'notFoundHandler' => function (ContainerInterface $container) {
        return new \App\Handler\NotFoundHandler(
            $container->get(ResponseFactoryInterface::class)
        );
    },
    

    // Not Allowed Handler
    'notAllowedHandler' => function (ContainerInterface $container) {
        return new \App\Handler\NotAllowedHandler(
            $container->get(ResponseFactoryInterface::class)
        );
    },

    // Base path middleware
    BasePathMiddleware::class => function (ContainerInterface $container) {
        return new BasePathMiddleware($container->get(App::class));
    },
    
    // Translation service
    'translator' => function (ContainerInterface $container) {
        $translator = new Translator('pt_BR');
        
        // Set the fallback locales
        $translator->setFallbackLocales(['en']);
        
        // Add the PHP file loader
        $translator->addLoader('php', new PhpFileLoader());
        $translator->addLoader('array', new ArrayLoader());
        
        // Add the translation resources
        $translator->addResource(
            'php',
            __DIR__ . '/../resources/lang/pt_BR/messages.php',
            'pt_BR',
            'messages'
        );
        
        // Add more languages here as needed
        // $translator->addResource('php', __DIR__ . '/../resources/lang/en/messages.php', 'en', 'messages');
        
        return $translator;
    },
    
    // User Repository (removido - usando Eloquent ORM)
];
