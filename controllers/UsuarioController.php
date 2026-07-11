<?php
require_once __DIR__ . '/../dao/UsuarioDAO.php';

class UsuarioController {
    private $usuarioDAO;

    public function __construct() {
        $this->usuarioDAO = new UsuarioDAO();
    }

    public function login() {
        require_once __DIR__ . '/../views/usuarios/login.php';
    }

    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $senha = $_POST['senha'] ?? '';

            $usuario = $this->usuarioDAO->readByEmail($email);

            if ($usuario && $usuario->verificarSenha($senha)) {
                $_SESSION['usuario_id'] = $usuario->getId();
                $_SESSION['usuario_nome'] = $usuario->getNome();
                header("Location: index.php");
                exit;
            } else {
                header("Location: index.php?action=login&error=invalid_credentials");
                exit;
            }
        }
    }

    public function register() {
        require_once __DIR__ . '/../views/usuarios/register.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = trim($_POST['nome'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $senha = $_POST['senha'] ?? '';

            // Check if email exists
            if ($this->usuarioDAO->readByEmail($email)) {
                header("Location: index.php?action=register&error=email_exists");
                exit;
            }

            // A senha será hasheada no construtor
            $usuario = new Usuario($nome, $email, $senha, '', new DateTime(), PermissaoUsuario::CLIENTE);

            if ($this->usuarioDAO->create($usuario)) {
                header("Location: index.php?action=login&success=registered");
                exit;
            } else {
                header("Location: index.php?action=register&error=create_failed");
                exit;
            }
        }
    }

    public function logout() {
        session_destroy();
        header("Location: index.php");
        exit;
    }

    public function meusAnuncios() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        require_once __DIR__ . '/../dao/UsuarioDAO.php';
        $usuarioDAO = new UsuarioDAO();
        $usuario = $usuarioDAO->readById($_SESSION['usuario_id']);
        if ($usuario && $usuario->getPermissoes() === PermissaoUsuario::CLIENTE) {
            header("Location: index.php?error=unauthorized");
            exit;
        }

        require_once __DIR__ . '/../dao/AnuncioDAO.php';
        $anuncioDAO = new AnuncioDAO();
        $anuncios = $anuncioDAO->readByUsuarioId($_SESSION['usuario_id']);
        
        require_once __DIR__ . '/../views/usuarios/meus_anuncios.php';
    }
}
