<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/ImagemAnuncio.php';

class ImagemAnuncioDAO {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    /**
     * Insere uma nova imagem de anúncio no banco de dados.
     */
    public function create(ImagemAnuncio $imagem): bool {
        $query = "INSERT INTO ImagemAnuncio (anuncioId, caminhoArquivo, imgPrincipal) 
                  VALUES (:anuncioId, :caminhoArquivo, :imgPrincipal)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindValue(':anuncioId', $imagem->getAnuncioId());
        $stmt->bindValue(':caminhoArquivo', $imagem->getCaminhoArquivo());
        $stmt->bindValue(':imgPrincipal', $imagem->isImgPrincipal(), PDO::PARAM_BOOL);

        if ($stmt->execute()) {
            $imagem->setId((int) $this->conn->lastInsertId());
            return true;
        }
        return false;
    }

    /**
     * Retorna todas as imagens de um anúncio específico.
     */
    public function findByAnuncioId(int $anuncioId): array {
        $query = "SELECT * FROM ImagemAnuncio WHERE anuncioId = :anuncioId ORDER BY imgPrincipal DESC, id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':anuncioId', $anuncioId, PDO::PARAM_INT);
        $stmt->execute();

        $imagens = [];
        while ($row = $stmt->fetch()) {
            $imagens[] = $this->hydrate($row);
        }
        return $imagens;
    }

    /**
     * Retorna a imagem principal (capa) de um anúncio.
     */
    public function findCapaByAnuncioId(int $anuncioId): ?ImagemAnuncio {
        $query = "SELECT * FROM ImagemAnuncio WHERE anuncioId = :anuncioId AND imgPrincipal = 1 LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':anuncioId', $anuncioId, PDO::PARAM_INT);
        $stmt->execute();

        if ($row = $stmt->fetch()) {
            return $this->hydrate($row);
        }
        return null;
    }

    /**
     * Remove uma imagem pelo ID.
     */
    public function delete(int $id): bool {
        $query = "DELETE FROM ImagemAnuncio WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    private function hydrate(array $row): ImagemAnuncio {
        $imagem = new ImagemAnuncio(
            (int) $row['anuncioId'],
            $row['caminhoArquivo'],
            (bool) $row['imgPrincipal']
        );
        $imagem->setId((int) $row['id']);
        return $imagem;
    }
}
