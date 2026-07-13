<?php
require_once __DIR__ . '/../dao/UsuarioDAO.php';

class AdminController {
    private $usuarioDAO;

    public function __construct() {
        $this->usuarioDAO = new UsuarioDAO();
    }


    public function admin() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        require_once __DIR__ . '/../dao/UsuarioDAO.php';
        $usuarioDAO = new UsuarioDAO();
        $usuario = $usuarioDAO->readById($_SESSION['usuario_id']);
        if ($usuario && $usuario->getPermissoes() !== PermissaoUsuario::ADMIN) {
            header("Location: index.php?error=unauthorized");
            exit;
        }

        require_once __DIR__ . '/../views/admin/home.php';
    }

    public function igrejaAdd() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        require_once __DIR__ . '/../dao/UsuarioDAO.php';
        $usuarioDAO = new UsuarioDAO();
        $usuario = $usuarioDAO->readById($_SESSION['usuario_id']);
        if ($usuario && $usuario->getPermissoes() !== PermissaoUsuario::ADMIN) {
            header("Location: index.php?error=unauthorized");
            exit;
        }

        require_once __DIR__ . '/../dao/BairroDAO.php';
        $bairroDAO = new BairroDAO();
        $bairros = $bairroDAO->readAll();

        require_once __DIR__ . '/../views/admin/igreja_add.php';
    }

    public function igrejaStore() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        require_once __DIR__ . '/../dao/UsuarioDAO.php';
        $usuarioDAO = new UsuarioDAO();
        $usuario = $usuarioDAO->readById($_SESSION['usuario_id']);
        if ($usuario && $usuario->getPermissoes() !== PermissaoUsuario::ADMIN) {
            header("Location: index.php?error=unauthorized");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../dao/IgrejaDAO.php';
            require_once __DIR__ . '/../model/Igreja.php';
            $igrejaDAO = new IgrejaDAO();
            $igreja = new Igreja(
                $_POST['nome'] ?? '',
                $_POST['pastorPresidente'] ?? null,
                $_POST['bairro_id'] ? (int)$_POST['bairro_id'] : null
            );
            $igrejaDAO->create($igreja);
        }

        header("Location: index.php?action=admin");
        exit;
    }

    public function igrejaList() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        require_once __DIR__ . '/../dao/UsuarioDAO.php';
        $usuarioDAO = new UsuarioDAO();
        $usuario = $usuarioDAO->readById($_SESSION['usuario_id']);
        if ($usuario && $usuario->getPermissoes() !== PermissaoUsuario::ADMIN) {
            header("Location: index.php?error=unauthorized");
            exit;
        }

        require_once __DIR__ . '/../dao/IgrejaDAO.php';
        $igrejaDAO = new IgrejaDAO();
        $igrejas = $igrejaDAO->readAll();

        require_once __DIR__ . '/../views/admin/igreja_list.php';
    }


}
