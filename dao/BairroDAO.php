<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/Bairro.php';

class BairroDAO {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function readAll() {
        $query = "SELECT * FROM Bairro ORDER BY nome ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $bairros = [];
        while ($row = $stmt->fetch()) {
            $bairro = new Bairro($row['nome'], $row['cidadeId']);
            $bairro->setId($row['id']);
            $bairros[] = $bairro;
        }
        return $bairros;
    }

    public function readById($id): ?Bairro {
        $query = "SELECT * FROM Bairro WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        if ($row = $stmt->fetch()) {
            $bairro = new Bairro($row['nome'], $row['cidadeId']);
            $bairro->setId($row['id']);
            return $bairro;
        }
        
        return null;
    }
}
