#!/bin/bash
set -e

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}🚀 Iniciando configuração do ambiente de desenvolvimento...${NC}"

# Verifica se o Docker está instalado
if ! command -v docker &> /dev/null; then
    echo -e "${YELLOW}❌ Docker não encontrado. Por favor, instale o Docker antes de continuar.${NC}"
    exit 1
fi



# Cria o arquivo .env se não existir
if [ ! -f .env ]; then
    echo -e "${YELLOW}📄 Criando arquivo .env a partir do exemplo...${NC}"
    cp .env.example .env
    
    # Gera uma chave de aplicativo aleatória
    sed -i "s/APP_KEY=/APP_KEY=$(openssl rand -base64 32)/" .env
    
    # Define o UID e GID do usuário atual
    echo "APP_UID=$(id -u)" >> .env
    echo "APP_GID=$(id -g)" >> .env
    
    echo -e "${GREEN}✅ Arquivo .env criado com sucesso!${NC}"
else
    echo -e "${GREEN}✅ Arquivo .env já existe.${NC}"
fi

# Cria diretórios necessários
mkdir -p logs
mkdir -p tmp

# Define permissões
chmod -R 777 logs
chmod -R 777 tmp

# Carrega as variáveis de ambiente
set -a
source .env
set +a

# Instala as dependências do Composer
echo -e "${YELLOW}📦 Instalando dependências do Composer...${NC}"
docker compose run --rm --no-deps composer install --no-interaction --prefer-dist --optimize-autoloader

# Ajusta as permissões do diretório vendor
if [ -d "vendor" ]; then
    echo -e "${YELLOW}🔒 Ajustando permissões do diretório vendor...${NC}"
    sudo chown -R $USER:$(id -gn) vendor/
    chmod -R 755 vendor/
fi

# Inicia os containers
echo -e "${YELLOW}🚀 Iniciando os containers Docker...${NC}"
docker compose up -d --build

# Executa as migrações
echo -e "${YELLOW}🔄 Executando migrações do banco de dados...${NC}"
docker compose exec web php database/migrate.php

echo -e "\n${GREEN}✨ Configuração concluída com sucesso!${NC}"
echo -e "\nAcesse a aplicação em: ${GREEN}http://localhost:8080${NC}"
echo -e "Acesse o phpMyAdmin em: ${GREEN}http://localhost:8081${NC}"
echo -e "\nPara parar os containers, execute: ${YELLOW}docker compose down${NC}"
