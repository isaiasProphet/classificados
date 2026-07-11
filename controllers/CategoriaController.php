<?php
require_once __DIR__ . '/../dao/SubCategoriaDAO.php';

class CategoriaController {
    private $subCategoriaDAO;

    public function __construct() {
        $this->subCategoriaDAO = new SubCategoriaDAO();
    }

    public function getSubcategorias() {
        header('Content-Type: application/json');
        
        $categoriaId = isset($_GET['categoria_id']) ? (int)$_GET['categoria_id'] : 0;
        
        if ($categoriaId > 0) {
            $subcategorias = $this->subCategoriaDAO->readByCategoriaId($categoriaId);
            $result = array_map(function($sub) {
                return [
                    'id' => $sub->getId(),
                    'nome' => $sub->getNome()
                ];
            }, $subcategorias);
            
            echo json_encode($result);
        } else {
            echo json_encode([]);
        }
        exit;
    }
}
