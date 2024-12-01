Aqui está o conteúdo para o seu README no GitHub, informando sobre a estrutura do banco de dados MySQL:

# Loja Virtual de Café

Este é um projeto de loja virtual de café, desenvolvido em PHP, com integração a um banco de dados MySQL para gerenciar produtos, clientes e pedidos.

## Banco de Dados

Para que o projeto funcione corretamente, é necessário criar um banco de dados MySQL com a estrutura a seguir:

### Criação do Banco de Dados

```sql
CREATE DATABASE loja_virtual_cafe;

Estrutura das Tabelas
Tabela produtos

Esta tabela armazena informações sobre os produtos disponíveis para venda.

CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10, 2) NOT NULL,
    imagem VARCHAR(255)
);

Tabela clientes

Esta tabela armazena informações sobre os clientes da loja.

CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    endereco TEXT
);

Tabela pedidos

Esta tabela armazena informações sobre os pedidos feitos pelos clientes.

CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_cliente VARCHAR(255) NOT NULL,
    email_cliente VARCHAR(255) NOT NULL,
    endereco_cliente TEXT NOT NULL,
    metodo_pagamento VARCHAR(50) NOT NULL,
    metodo_entrega VARCHAR(50) NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    data_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

Tabela itens_pedido

Esta tabela armazena os itens de cada pedido, com o produto, quantidade e preço.

CREATE TABLE itens_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT,
    produto_nome VARCHAR(255) NOT NULL,
    quantidade INT NOT NULL,
    preco DECIMAL(10, 2) NOT NULL,
    total_item DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
);

Como configurar o banco de dados

    Acesse o MySQL e crie o banco de dados com o comando:

CREATE DATABASE loja_virtual_cafe;

Selecione o banco de dados para utilizar:

    USE loja_virtual_cafe;

    Crie as tabelas utilizando os scripts fornecidos acima.

Após configurar o banco de dados, o sistema estará pronto para gerenciar os dados da loja virtual de café.
Como Rodar o Projeto
Requisitos

    PHP 7.4 ou superior
    MySQL

Configuração

    Clone o repositório para a sua máquina:

git clone

Configure o arquivo de conexão com o banco de dados no projeto

Importe o banco de dados utilizando os scripts SQL fornecidos.

Inicie o servidor PHP:

    php -S localhost:8000

Agora, acesse o sistema no navegador em http://localhost:8000.
Contribuição

    Fork este repositório.
    Crie uma branch para a sua modificação.
    Envie um pull request para a branch main.

Licença

Este projeto é licenciado sob a Licença MIT - veja o arquivo LICENSE para mais detalhes.
