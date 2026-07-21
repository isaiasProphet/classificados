<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/Igreja.php';

class IgrejaDAO {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function readAll() {
        $query = "SELECT i.*, b.nome AS bairro_nome 
                FROM Igreja i
                LEFT JOIN Bairro b ON i.bairro_id = b.id
                ORDER BY i.nome ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $igrejas = [];
        while ($row = $stmt->fetch()) {
            $igreja = new Igreja(
                $row['nome'],
                $row['pastor_presidente'] ?? null,
                $row['bairro_id'] ?? null
            );
            $igreja->setId($row['id']);
            $igreja->setBairroNome($row['bairro_nome'] ?? null);
            $igrejas[] = $igreja;
        }
        
        return $igrejas;
    }

    public function readPagination(int $page, int $limit = 15): array {
        $offset = ($page - 1) * $limit;
        $totalQuery = "SELECT COUNT(*) as total FROM Igreja";
        $stmt = $this->conn->prepare($totalQuery);
        $stmt->execute();
        $total = $stmt->fetch()['total'];
        $pages = (int) ceil($total / $limit);
        
        $query = "SELECT i.*, b.nome AS bairro_nome 
                FROM Igreja i
                LEFT JOIN Bairro b ON i.bairro_id = b.id
                ORDER BY i.nome ASC 
                LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        $igrejas = [];
        while ($row = $stmt->fetch()) {
            $igreja = new Igreja(
                $row['nome'],
                $row['pastor_presidente'] ?? null,
                $row['bairro_id'] ?? null
            );
            $igreja->setId($row['id']);
            $igreja->setBairroNome($row['bairro_nome']);
            $igrejas[] = $igreja;
        }
        
        return ['data' => $igrejas, 'total' => $total, 'pages' => $pages];
    }

    public function readOne(int $id): ?Igreja {
        $query = "SELECT i.*, b.nome AS bairro_nome 
                  FROM Igreja i
                  LEFT JOIN Bairro b ON i.bairro_id = b.id
                  WHERE i.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        if ($row = $stmt->fetch()) {
            $igreja = new Igreja(
                $row['nome'],
                $row['pastor_presidente'] ?? null,
                $row['bairro_id'] ?? null
            );
            $igreja->setId($row['id']);
            $igreja->setBairroNome($row['bairro_nome']);
            return $igreja;
        }
        
        return null;
    }

    public function create(Igreja $igreja): int {
         var_dump($igreja);
       
        $query = "INSERT INTO Igreja (nome, pastor_presidente, bairro_id) 
                VALUES (:nome, :pastor_presidente, :bairro_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':nome', $igreja->getNome());
        $stmt->bindValue(':pastor_presidente', $igreja->getPastorPresidente() ?? null);
        $stmt->bindValue(':bairro_id', $igreja->getBairro_id() ?? null);
        $stmt->execute();
        return $this->conn->lastInsertId();
    }

    public function update(int $id, Igreja $igreja): bool {
        $query = "UPDATE Igreja 
                  SET nome = :nome, pastor_presidente = :pastor_presidente, bairro_id = :bairro_id 
                  WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':nome', $igreja->getNome());
    $stmt->bindValue(':pastor_presidente', $igreja->getPastorPresidente() ?? null);
    $stmt->bindValue(':bairro_id', $igreja->getBairroId() ?? null);
    $stmt->execute();
    
    return $stmt->rowCount() > 0;
}

public function delete($id): bool {
    $query = "DELETE FROM Igreja WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->rowCount() > 0;
}

}
