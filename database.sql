CREATE DATABASE IF NOT EXISTS classificados;
USE classificados;

-- Tabela Categoria
CREATE TABLE IF NOT EXISTS Categoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL
);

-- Tabela SubCategoria
CREATE TABLE IF NOT EXISTS SubCategoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    categoria_id INT,
    slug VARCHAR(255) NOT NULL,
    FOREIGN KEY (categoria_id) REFERENCES Categoria(id) ON DELETE SET NULL
);

-- Tabela Usuario
CREATE TABLE IF NOT EXISTS Usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20),
    dataCadastro DATETIME DEFAULT CURRENT_TIMESTAMP,
    permissoes ENUM('admin', 'moderador', 'anunciante', 'cliente') DEFAULT 'anunciante'
);

-- Tabela Anuncio
CREATE TABLE IF NOT EXISTS Anuncio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT,
    subCategoriaId INT,
    usuarioId INT,
    preco DECIMAL(10, 2) DEFAULT 0.00,
    status ENUM('ativo', 'pausado', 'vendido', 'pendente_aprovacao') DEFAULT 'pendente_aprovacao',
    visualizacoes INT DEFAULT 0,
    dataCriacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    dataAtualizacao DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (subCategoriaId) REFERENCES SubCategoria(id) ON DELETE SET NULL,
    FOREIGN KEY (usuarioId) REFERENCES Usuario(id) ON DELETE CASCADE
);

-- Tabela Bairro
-- Nota: Como o model de 'Cidade' não foi encontrado, cidadeId está sendo referenciado como um campo simples (sem FK correspondente gerada aqui).
CREATE TABLE IF NOT EXISTS Bairro (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    cidadeId INT NOT NULL
);

-- Tabela Localizacao
CREATE TABLE IF NOT EXISTS Localizacao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    anuncioId INT NOT NULL,
    bairroId INT,
    rua VARCHAR(255),
    numero VARCHAR(50),
    linkMaps TEXT,
    FOREIGN KEY (anuncioId) REFERENCES Anuncio(id) ON DELETE CASCADE,
    FOREIGN KEY (bairroId) REFERENCES Bairro(id) ON DELETE SET NULL
);

-- Tabela ImagemAnuncio
CREATE TABLE IF NOT EXISTS ImagemAnuncio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    anuncioId INT NOT NULL,
    caminhoArquivo VARCHAR(255) NOT NULL,
    imgPrincipal BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (anuncioId) REFERENCES Anuncio(id) ON DELETE CASCADE
);
