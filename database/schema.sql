-- Criar banco de dados
CREATE DATABASE IF NOT EXISTS juntanova CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE juntanova;

-- Tabela de usuários
CREATE TABLE IF NOT EXISTS usuarios (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nome VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  telefone VARCHAR(20) NOT NULL,
  cpf VARCHAR(14) NOT NULL,
  cep VARCHAR(10),
  rua VARCHAR(255),
  numero VARCHAR(20),
  complemento VARCHAR(255),
  bairro VARCHAR(255),
  cidade VARCHAR(255),
  estado VARCHAR(2),
  pais VARCHAR(100) DEFAULT 'Brasil',
  is_admin BOOLEAN DEFAULT FALSE,
  data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_email (email),
  INDEX idx_is_admin (is_admin)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de produtos
CREATE TABLE IF NOT EXISTS produtos (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nome VARCHAR(255) NOT NULL DEFAULT 'Junta Nova',
  descricao TEXT,
  preco DECIMAL(10,2) NOT NULL DEFAULT 139.00,
  preco_promocional DECIMAL(10,2),
  estoque INT NOT NULL DEFAULT 0,
  peso VARCHAR(20) DEFAULT '45g',
  capsulas INT DEFAULT 30,
  dosagem VARCHAR(50) DEFAULT '500mg cada',
  tipo VARCHAR(100) DEFAULT '100% Natural',
  ativo BOOLEAN DEFAULT TRUE,
  data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_ativo (ativo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de imagens do produto
CREATE TABLE IF NOT EXISTS produto_imagens (
  id INT PRIMARY KEY AUTO_INCREMENT,
  produto_id INT NOT NULL,
  caminho VARCHAR(255) NOT NULL,
  ordem INT DEFAULT 0,
  FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE,
  INDEX idx_produto_id (produto_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de benefícios do produto
CREATE TABLE IF NOT EXISTS produto_beneficios (
  id INT PRIMARY KEY AUTO_INCREMENT,
  produto_id INT NOT NULL,
  beneficio TEXT NOT NULL,
  FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE,
  INDEX idx_produto_id (produto_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de pedidos
CREATE TABLE IF NOT EXISTS pedidos (
  id INT PRIMARY KEY AUTO_INCREMENT,
  numero_pedido VARCHAR(20) NOT NULL UNIQUE,
  usuario_id INT NOT NULL,
  cep VARCHAR(10),
  rua VARCHAR(255),
  numero VARCHAR(20),
  complemento VARCHAR(255),
  bairro VARCHAR(255),
  cidade VARCHAR(255),
  estado VARCHAR(2),
  pais VARCHAR(100) DEFAULT 'Brasil',
  valor_produtos DECIMAL(10,2) NOT NULL,
  valor_frete DECIMAL(10,2) NOT NULL,
  valor_total DECIMAL(10,2) NOT NULL,
  status ENUM('pendente', 'pago', 'processando', 'enviado', 'entregue', 'cancelado') DEFAULT 'pendente',
  metodo_pagamento VARCHAR(50),
  status_pagamento VARCHAR(50),
  mercadopago_id VARCHAR(255),
  mercadopago_status VARCHAR(50),
  data_aprovacao TIMESTAMP NULL,
  data_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
  INDEX idx_usuario_id (usuario_id),
  INDEX idx_status (status),
  INDEX idx_data_pedido (data_pedido),
  INDEX idx_numero_pedido (numero_pedido)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de itens do pedido
CREATE TABLE IF NOT EXISTS pedido_itens (
  id INT PRIMARY KEY AUTO_INCREMENT,
  pedido_id INT NOT NULL,
  produto_id INT NOT NULL,
  nome VARCHAR(255) NOT NULL,
  quantidade INT NOT NULL,
  preco DECIMAL(10,2) NOT NULL,
  subtotal DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
  FOREIGN KEY (produto_id) REFERENCES produtos(id),
  INDEX idx_pedido_id (pedido_id),
  INDEX idx_produto_id (produto_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de depoimentos
CREATE TABLE IF NOT EXISTS depoimentos (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nome VARCHAR(255) NOT NULL,
  cidade VARCHAR(255) NOT NULL,
  estado VARCHAR(2) NOT NULL,
  depoimento TEXT NOT NULL,
  estrelas INT DEFAULT 5 CHECK (estrelas >= 1 AND estrelas <= 5),
  aprovado BOOLEAN DEFAULT TRUE,
  destaque BOOLEAN DEFAULT FALSE,
  data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_aprovado (aprovado),
  INDEX idx_destaque (destaque)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de sessões (para express-session)
CREATE TABLE IF NOT EXISTS sessions (
  session_id VARCHAR(128) PRIMARY KEY,
  expires INT UNSIGNED NOT NULL,
  data MEDIUMTEXT,
  INDEX idx_expires (expires)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
