# Slim Framework 4 - Projeto Base

## 🚀 Visão Geral

Este é um projeto base para desenvolvimento de aplicações web utilizando o Slim Framework 4. O projeto já vem configurado com Docker, banco de dados MariaDB, Redis para cache, e suporte a internacionalização.

## 🛠️ Tecnologias Principais

### Backend
- **PHP 8.3** - Última versão estável do PHP
- **Slim Framework 4** - Micro-framework PHP para APIs e aplicações web
- **Eloquent ORM** - ORM elegante para trabalhar com banco de dados
- **Plates** - Sistema de templates simples, rápido e seguro
- **PHP-DI** - Container de injeção de dependências
- **Monolog** - Sistema de logging

### Banco de Dados
- **MariaDB 12** - Sistema gerenciador de banco de dados relacional
- **Redis** - Armazenamento de cache em memória

### Infraestrutura
- **Docker** - Containers para desenvolvimento e produção
- **Docker Compose** - Orquestração de containers
- **Apache** - Servidor web
- **PHPMyAdmin** - Interface web para gerenciamento do banco de dados

### Ferramentas de Desenvolvimento
- **Composer** - Gerenciador de dependências PHP
- **PHPUnit** - Framework para testes unitários
- **PHP_CodeSniffer** - Ferramenta de análise estática

## 📦 Estrutura do Projeto

```
.
├── bin/                  # Scripts úteis
│   └── setup.sh          # Script de configuração inicial
├── config/               # Arquivos de configuração
├── database/             # Migrações e seeds
│   ├── migrations/       # Migrações do banco de dados
│   └── migrate.php       # Script de migração
├── docker/               # Configurações do Docker
│   ├── apache/           # Configuração do Apache
│   ├── healthcheck/      # Scripts de verificação de saúde
│   ├── mariadb/          # Configuração do MariaDB
│   └── php/              # Configuração do PHP
├── public/               # Pasta pública do servidor web
│   ├── index.php         # Ponto de entrada da aplicação
│   └── .htaccess        # Configurações do Apache
├── resources/            # Recursos (templates, traduções, etc.)
│   └── lang/            # Arquivos de tradução
│       └── pt_BR/       # Traduções em português
├── src/                  # Código-fonte da aplicação
│   ├── Controllers/     # Controladores
│   ├── Models/          # Modelos do Eloquent
│   ├── Handlers/        # Manipuladores de erros
│   ├── app.php          # Configuração da aplicação
│   ├── dependencies.php # Injeção de dependências
│   ├── helpers.php      # Funções auxiliares globais
│   ├── middleware.php   # Middleware global
│   ├── routes.php       # Definição de rotas
│   └── settings.php     # Configurações da aplicação
├── templates/            # Templates usando Plates
├── var/                  # Arquivos temporários
│   └── logs/            # Logs da aplicação
├── .env                 # Variáveis de ambiente (não versionado)
├── .env.example         # Exemplo de variáveis de ambiente
├── .gitignore           # Arquivos ignorados pelo Git
├── composer.json        # Dependências do projeto
├── composer.lock        # Versões travadas das dependências
└── docker-compose.yml   # Configuração dos serviços Docker
```

## 🚀 Como Começar

### Pré-requisitos

- Docker e Docker Compose instalados
- Git para clonar o repositório
- Acesso ao terminal (Linux/Mac) ou Git Bash (Windows)

### Configuração Inicial

1. **Clone o repositório**
   ```bash
   git clone [URL_DO_REPOSITORIO]
   cd nome-do-projeto
   ```

2. **Execute o script de setup**
   ```bash
   chmod +x bin/setup.sh
   ./bin/setup.sh
   ```
   
   Este script irá:
   - Criar o arquivo `.env` a partir do `.env.example`
   - Instalar as dependências do Composer
   - Configurar as permissões dos diretórios
   - Iniciar os containers Docker
   - Executar as migrações do banco de dados

3. **Acesse a aplicação**
   - Aplicação: http://localhost:8080
   - PHPMyAdmin: http://localhost:8081
     - Servidor: `mariadb`
     - Usuário: `slim`
     - Senha: `slim123`
     - Banco de dados: `slimdb`

## 🛠 Comandos Úteis

### Gerenciamento de Containers

```bash
# Iniciar os containers em segundo plano
docker compose up -d

# Parar os containers
docker compose down

# Verificar status dos containers
docker compose ps

# Visualizar logs dos containers
docker compose logs -f
```

### Desenvolvimento

```bash
# Instalar/atualizar dependências
docker compose run --rm composer install

# Executar migrações
docker compose exec web php database/migrate.php

# Acessar o container web
docker compose exec web bash

# Executar testes
docker compose exec web ./vendor/bin/phpunit
```

## 🔧 Configurações Avançadas

### Variáveis de Ambiente

O arquivo `.env` contém todas as configurações da aplicação. As principais são:

```
# Configurações do banco de dados
DB_HOST=mariadb
DB_DATABASE=slimdb
DB_USERNAME=slim
DB_PASSWORD=slim123

# Configurações da aplicação
APP_ENV=development
APP_DEBUG=true
APP_LOCALE=pt_BR

# Configurações de cache
CACHE_DRIVER=redis
REDIS_HOST=redis
```

### Adicionando Novas Dependências

Para adicionar uma nova dependência ao projeto:

```bash
docker compose run --rm composer require vendor/package
```

## 📚 Documentação Adicional

- [Documentação do Slim Framework 4](https://www.slimframework.com/docs/v4/)
- [Documentação do Eloquent ORM](https://laravel.com/docs/10.x/eloquent)
- [Documentação do Plates](https://platesphp.com/)
- [Documentação do Docker](https://docs.docker.com/)

## 🤝 Contribuição

1. Faça um fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/nova-feature`)
3. Commit suas alterações (`git commit -m 'Adiciona nova feature'`)
4. Push para a branch (`git push origin feature/nova-feature`)
5. Abra um Pull Request

## 📄 Licença

Este projeto está licenciado sob a licença MIT - veja o arquivo [LICENSE](LICENSE) para detalhes.
3. Run `docker-compose up -d` to start the development environment
4. Install dependencies with `composer install`

### Development
- The application will be available at `http://localhost:8080`
- PHPMyAdmin is available at `http://localhost:8081`

## Migration Notes
- Following SLIM 4 patterns and best practices
- Maintaining backward compatibility where possible
- Implementing modern PHP 8.3 features
- Upgrading frontend to Inspinia By WebAppLayers

## License
This project is proprietary software.
