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

            if (strlen($senha) < 8 || !preg_match('/[A-Z]/', $senha) || !preg_match('/[a-z]/', $senha) || !preg_match('/[0-9]/', $senha) || !preg_match('/[\W_]/', $senha)) {
                header("Location: index.php?action=register&error=weak_password");
                exit;
            }

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

    public function perfil() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        require_once __DIR__ . '/../dao/UsuarioDAO.php';
        $usuarioDAO = new UsuarioDAO();
        $usuario = $usuarioDAO->readById($_SESSION['usuario_id']);
        
        require_once __DIR__ . '/../views/usuarios/perfil.php';
    }

    public function atualizarPerfil() {
        if (!isset($_SESSION['usuario_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=login");
            exit;
        }

        require_once __DIR__ . '/../dao/UsuarioDAO.php';
        $usuarioDAO = new UsuarioDAO();
        $usuario = $usuarioDAO->readById($_SESSION['usuario_id']);

        if ($usuario) {
            $usuario->setNome(trim($_POST['nome'] ?? $usuario->getNome()));
            
            // Check if email changed and if new email is already taken
            $novoEmail = trim($_POST['email'] ?? $usuario->getEmail());
            if ($novoEmail !== $usuario->getEmail()) {
                $existente = $usuarioDAO->readByEmail($novoEmail);
                if ($existente) {
                    header("Location: index.php?action=meu_perfil&error=email_exists");
                    exit;
                }
                $usuario->setEmail($novoEmail);
            }

            $usuario->setTelefone(trim($_POST['telefone'] ?? $usuario->getTelefone()));
            $usuario->setCargoIgreja(trim($_POST['cargo_igreja'] ?? $usuario->getCargoIgreja()));
            $usuario->setSobreMim(trim($_POST['sobre_mim'] ?? $usuario->getSobreMim()));

            $novaSenha = $_POST['senha'] ?? '';
            if (!empty($novaSenha)) {
                $senhaAtual = $_POST['senha_atual'] ?? '';
                if (!$usuario->verificarSenha($senhaAtual)) {
                    header("Location: index.php?action=meu_perfil&error=invalid_current_password");
                    exit;
                }
                if (strlen($novaSenha) < 8 || !preg_match('/[A-Z]/', $novaSenha) || !preg_match('/[a-z]/', $novaSenha) || !preg_match('/[0-9]/', $novaSenha) || !preg_match('/[\W_]/', $novaSenha)) {
                    header("Location: index.php?action=meu_perfil&error=weak_password");
                    exit;
                }
                $usuario->setSenha($novaSenha);
            }

            if ($usuarioDAO->update($usuario)) {
                $_SESSION['usuario_nome'] = $usuario->getNome(); // Atualiza nome na sessão
                header("Location: index.php?action=meu_perfil&success=updated");
                exit;
            } else {
                header("Location: index.php?action=meu_perfil&error=update_failed");
                exit;
            }
        }
        
        header("Location: index.php");
        exit;
    }
}
