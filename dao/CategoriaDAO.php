<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/Categoria.php';

class CategoriaDAO {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function readAll() {
        $query = "SELECT * FROM Categoria";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $categorias = [];
        while ($row = $stmt->fetch()) {
            $categoria = new Categoria($row['nome']);
            $categoria->setId($row['id']);
            $categorias[] = $categoria;
        }
        return $categorias;
    }

    public function readById($id) {
        $query = "SELECT * FROM Categoria WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        if ($row = $stmt->fetch()) {
            $categoria = new Categoria($row['nome']);
            $categoria->setId($row['id']);
            return $categoria;
        }
        return null;
    }
}
