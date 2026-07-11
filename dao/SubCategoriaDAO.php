<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/SubCategoria.php';

class SubCategoriaDAO {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function readByCategoriaId($categoriaId) {
        $query = "SELECT * FROM SubCategoria WHERE categoria_id = :categoria_id ORDER BY nome ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":categoria_id", $categoriaId);
        $stmt->execute();
        
        $subcategorias = [];
        while ($row = $stmt->fetch()) {
            $subcategoria = new SubCategoria($row['nome'], $row['categoria_id'], $row['slug']);
            $subcategoria->setId($row['id']);
            $subcategorias[] = $subcategoria;
        }
        return $subcategorias;
    }

    public function readById($id) {
        $query = "SELECT * FROM SubCategoria WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        if ($row = $stmt->fetch()) {
            $subcategoria = new SubCategoria($row['nome'], $row['categoria_id'], $row['slug']);
            $subcategoria->setId($row['id']);
            return $subcategoria;
        }
        return null;
    }
}
