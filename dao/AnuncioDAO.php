<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/Anuncio.php';
require_once __DIR__ . '/ImagemAnuncioDAO.php';

class AnuncioDAO {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function readAll() {
        $query = "SELECT * FROM Anuncio ORDER BY dataCriacao DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $anuncios = [];
        $imagemDAO = new ImagemAnuncioDAO();
        while ($row = $stmt->fetch()) {
            $anuncio = $this->hydrateAnuncio($row);
            $capa = $imagemDAO->findCapaByAnuncioId($anuncio->getId());
            if ($capa) {
                $anuncio->setCapaPath($capa->getCaminhoArquivo());
            }
            $anuncios[] = $anuncio;
        }
        return $anuncios;
    }

    public function search($term) {
        $query = "SELECT * FROM Anuncio WHERE titulo LIKE :term OR descricao LIKE :term ORDER BY dataCriacao DESC";
        $stmt = $this->conn->prepare($query);
        $searchTerm = "%{$term}%";
        $stmt->bindParam(":term", $searchTerm);
        $stmt->execute();

        $anuncios = [];
        $imagemDAO = new ImagemAnuncioDAO();
        while ($row = $stmt->fetch()) {
            $anuncio = $this->hydrateAnuncio($row);
            $capa = $imagemDAO->findCapaByAnuncioId($anuncio->getId());
            if ($capa) {
                $anuncio->setCapaPath($capa->getCaminhoArquivo());
            }
            $anuncios[] = $anuncio;
        }
        return $anuncios;
    }

    public function readById($id) {
        $query = "SELECT * FROM Anuncio WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        if ($row = $stmt->fetch()) {
            $anuncio = $this->hydrateAnuncio($row);
            $imagemDAO = new ImagemAnuncioDAO();
            $capa = $imagemDAO->findCapaByAnuncioId($anuncio->getId());
            if ($capa) {
                $anuncio->setCapaPath($capa->getCaminhoArquivo());
            }
            return $anuncio;
        }
        return null;
    }

    public function create(Anuncio $anuncio) {
        $query = "INSERT INTO Anuncio (titulo, descricao, subCategoriaId, usuarioId, preco, status) 
                  VALUES (:titulo, :descricao, :subCategoriaId, :usuarioId, :preco, :status)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindValue(':titulo', $anuncio->getTitulo());
        $stmt->bindValue(':descricao', $anuncio->getDescricao());
        $stmt->bindValue(':subCategoriaId', $anuncio->getSubCategoriaId() > 0 ? $anuncio->getSubCategoriaId() : null);
        $stmt->bindValue(':usuarioId', $anuncio->getUsuarioId());
        $stmt->bindValue(':preco', $anuncio->getPreco());
        $stmt->bindValue(':status', $anuncio->getStatus()->value);

        if ($stmt->execute()) {
            $anuncio->setId($this->conn->lastInsertId());
            return true;
        }
        return false;
    }

    public function update(Anuncio $anuncio) {
        $query = "UPDATE Anuncio 
                  SET titulo = :titulo, descricao = :descricao, subCategoriaId = :subCategoriaId, preco = :preco 
                  WHERE id = :id AND usuarioId = :usuarioId";
        $stmt = $this->conn->prepare($query);

        $stmt->bindValue(':titulo', $anuncio->getTitulo());
        $stmt->bindValue(':descricao', $anuncio->getDescricao());
        $stmt->bindValue(':subCategoriaId', $anuncio->getSubCategoriaId() > 0 ? $anuncio->getSubCategoriaId() : null);
        $stmt->bindValue(':preco', $anuncio->getPreco());
        $stmt->bindValue(':id', $anuncio->getId());
        $stmt->bindValue(':usuarioId', $anuncio->getUsuarioId());

        return $stmt->execute();
    }

    private function hydrateAnuncio($row) {
        $dataCriacao = new DateTime($row['dataCriacao']);
        $dataAtualizacao = new DateTime($row['dataAtualizacao']);
        $status = StatusAnuncio::from($row['status']);

        $anuncio = new Anuncio(
            $row['titulo'],
            $row['descricao'] ?? '',
            $row['subCategoriaId'] ?? 0,
            $row['usuarioId'] ?? 0,
            (float) $row['preco'],
            $status,
            (int) $row['visualizacoes'],
            $dataCriacao,
            $dataAtualizacao
        );
        $anuncio->setId($row['id']);
        
        return $anuncio;
    }

    public function readByUsuarioId($usuarioId) {
        $query = "SELECT * FROM Anuncio WHERE usuarioId = :usuarioId ORDER BY dataCriacao DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":usuarioId", $usuarioId);
        $stmt->execute();

        $anuncios = [];
        $imagemDAO = new ImagemAnuncioDAO();
        while ($row = $stmt->fetch()) {
            $anuncio = $this->hydrateAnuncio($row);
            $capa = $imagemDAO->findCapaByAnuncioId($anuncio->getId());
            if ($capa) {
                $anuncio->setCapaPath($capa->getCaminhoArquivo());
            }
            $anuncios[] = $anuncio;
        }
        return $anuncios;
    }
}
