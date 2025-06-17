<?php

require __DIR__ . '/../vendor/autoload.php';

// Carrega as variáveis de ambiente
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Configura o Eloquent
$capsule = new Illuminate\Database\Capsule\Manager;
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => getenv('DB_HOST') ?: 'mariadb',
    'database'  => getenv('DB_NAME') ?: 'slimdb',
    'username'  => getenv('DB_USER') ?: 'slim',
    'password'  => getenv('DB_PASS') ?: 'slim123',
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
]);

// Torna a instância do Eloquent disponível globalmente
$capsule->setAsGlobal();

// Configura o facade do Eloquent
$capsule->bootEloquent();

// Carrega o Schema Builder
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

// Cria a tabela de usuários diretamente, sem usar o facade
$schema = $capsule->schema();

if (!$schema->hasTable('users')) {
    $schema->create('users', function (Blueprint $table) {
        $table->id();
        $table->string('username')->unique();
        $table->string('email')->unique();
        $table->string('password');
        $table->string('name');
        $table->string('role')->default('user');
        $table->boolean('is_active')->default(true);
        $table->boolean('is_verified')->default(false);
        $table->string('verification_token')->nullable();
        $table->rememberToken();
        $table->timestamps();
        $table->softDeletes();
    });
    
    echo "Tabela 'users' criada com sucesso!\n";
} else {
    echo "A tabela 'users' já existe.\n";
}

echo "Operação concluída com sucesso!\n";
