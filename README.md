# SLIM Framework 4 Migration Project

## Project Overview
This project involves migrating an existing application from SLIM Framework 3 to SLIM Framework 4 while maintaining the same folder structure but following SLIM 4 patterns and best practices.

## Technical Stack
- **Backend**:
  - PHP 8.3
  - SLIM Framework 4
  - Composer for dependency management

- **Database**:
  - MariaDB 12

- **Frontend**:
  - Migrating from AdminLTE 2 to Inspinia By WebAppLayers

- **Development Environment**:
  - Docker
  - Apache web server

## Project Structure
```
slim4/
├── config/               # Configuration files
├── public/               # Web server root
│   └── index.php         # Application entry point
├── src/                  # Application source code
│   ├── Middleware/       # Custom middleware
│   ├── Routes/           # Route definitions
│   └── ...
├── templates/            # View templates
│   └── ...
├── tests/               # Test files
├── var/                  # Temporary files, logs, etc.
├── vendor/               # Composer dependencies
├── .env                  # Environment variables
├── .env.example          # Example environment variables
├── composer.json         # Project dependencies
├── composer.lock         # Locked dependencies
└── README.md             # This file

## 🚀 Guia de Configuração Rápida

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

- **Iniciar os containers**:
  ```bash
  docker compose up -d
  ```

- **Parar os containers**:
  ```bash
  docker compose down
  ```

- **Instalar/atualizar dependências**:
  ```bash
  docker compose run --rm composer install
  ```

- **Executar migrações**:
  ```bash
  docker compose exec web php database/migrate.php
  ```

- **Acessar o container web**:
  ```bash
  docker compose exec web bash
  ```

## 🔒 Configuração de Permissões

Se encontrar erros de permissão, execute:

```bash
# Ajustar permissões dos diretórios
chmod -R 777 logs/
chmod -R 777 tmp/

# Se necessário, ajuste o dono do diretório vendor
sudo chown -R $USER:$(id -gn) vendor/
```

## 🐛 Solução de Problemas

### Erro de permissão ao instalar dependências

Se o Composer falhar com erros de permissão, tente:

```bash
# Remova o diretório vendor existente (se houver)
rm -rf vendor/

# Execute o composer com as permissões corretas
docker compose run --rm --no-deps composer install --no-interaction --prefer-dist --optimize-autoloader
```

### Erro ao acessar o banco de dados

Verifique se o serviço do banco de dados está em execução:

```bash
docker compose ps
```

Se o MariaDB não estiver saudável, tente:

```bash
docker compose down -v
docker compose up -d
```

## 📦 Estrutura do Projeto

```
slim4/
├── bin/                 # Scripts úteis
│   └── setup.sh         # Script de configuração inicial
├── config/              # Arquivos de configuração
├── database/            # Migrações e seeds
├── docker/              # Configurações do Docker
│   ├── mariadb/         # Configuração do MariaDB
│   └── php/             # Configuração do PHP
├── public/              # Pasta pública do servidor web
│   ├── index.php        # Ponto de entrada da aplicação
│   └── .htaccess       # Configurações do Apache
├── resources/           # Recursos (templates, traduções, etc.)
│   └── lang/           # Arquivos de tradução
│       └── pt_BR/      # Traduções em português
├── src/                 # Código-fonte da aplicação
│   ├── Controllers/    # Controladores
│   ├── Models/         # Modelos
│   ├── Services/       # Serviços
│   ├── Handlers/       # Manipuladores de erros
│   ├── app.php         # Configuração da aplicação
│   ├── dependencies.php # Injeção de dependências
│   ├── helpers.php     # Funções auxiliares
│   ├── middleware.php  # Middleware global
│   ├── routes.php      # Definição de rotas
│   └── settings.php    # Configurações da aplicação
├── templates/           # Templates de visualização
├── tests/              # Testes automatizados
├── var/                # Arquivos temporários
│   └── cache/         # Cache da aplicação
├── .env                # Variáveis de ambiente (não versionado)
├── .env.example        # Exemplo de variáveis de ambiente
├── .gitignore          # Arquivos ignorados pelo Git
├── composer.json       # Dependências do projeto
├── composer.lock       # Versões travadas das dependências
└── docker-compose.yml  # Configuração dos serviços Docker
```

## 📝 Licença

Este projeto está licenciado sob a licença MIT - veja o arquivo [LICENSE](LICENSE) para detalhes.

## 🤝 Contribuição

1. Faça um fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas alterações (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request
1. Clone the repository
2. Copy `.env.example` to `.env` and configure your environment variables
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
