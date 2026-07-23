<?php

require_once __DIR__ . '/../dao/UsuarioDAO.php';
require_once __DIR__ . '/MailerController.php';

class UsuarioController {

    private $usuarioDAO;
    private $mailController;

    public function __construct() {
        $this->usuarioDAO = new UsuarioDAO();
        $this->mailController = new MailerController();
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
                if (!$usuario->getAtivo()) {
                    header("Location: index.php?action=login&error=account_inactive");
                    exit;
                }

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


    private function validarTokenTurnstile(){
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['cf-turnstile-response'] ?? '';
            $secretKey = $_ENV['TURNSTILE_SECRET_KEY'] ?? 'xxxxxxxxxxxxxxxxxxxxxxx';

            if (empty($token)) {
                die('Por favor, confirme que você não é um robô.');
            }

            $data = http_build_query([
                'secret'   => $secretKey,
                'response' => $token,
                'remoteip' => $_SERVER['REMOTE_ADDR'] ?? null
            ]);

            $options = [
                'http' => [
                    'header'  => "Content-Type: application/x-www-form-urlencoded\r\n" .
                                "Content-Length: " . strlen($data) . "\r\n",
                    'method'  => 'POST',
                    'content' => $data,
                    'timeout' => 10
                ]
            ];

            $context  = stream_context_create($options);
            $response = @file_get_contents('https://challenges.cloudflare.com/turnstile/v0/siteverify', false, $context);

            if ($response === false) {
                die("Erro ao conectar com a API de validação.");
            }

            $resultado = json_decode($response, true);

            if (!empty($resultado['success'])) {
                $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
                return true;
            } else {
                header("Location: index.php?action=register&error=erro_captcha");
                exit;
            }
        }

    }


    public function store() {
        $this->validarTokenTurnstile();
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
                // criar log para gravar erro de envio de email
                $this->mailController->sendEmailWelcome($usuario->getEmail(), $usuario->getNome());
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

        require_once __DIR__ . '/../dao/IgrejaDAO.php';
        $igrejaDAO = new IgrejaDAO();
        $igrejas = $igrejaDAO->readAll();
        
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
            if (isset($_POST['igreja_id'])) {
                $usuario->setIgrejaId(intval($_POST['igreja_id']));
            }
            $usuario->setCargoIgreja(trim($_POST['cargo_igreja'] ?? $usuario->getCargoIgreja()));
            $usuario->setSobreMim(trim($_POST['sobre_mim'] ?? $usuario->getSobreMim()));

            // Tratamento do upload da foto de perfil
            if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../uploads/usuarios/';
                $fileInfo = pathinfo($_FILES['foto_perfil']['name']);
                $extension = strtolower($fileInfo['extension']);
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
                
                if (in_array($extension, $allowedExtensions)) {
                    $newFileName = 'user_' . $usuario->getId() . '_' . time() . '.' . $extension;
                    $targetFile = $uploadDir . $newFileName;
                    
                    if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $targetFile)) {
                        // Deletar foto antiga se existir
                        $fotoAntiga = $usuario->getFotoPerfilPath();
                        if (!empty($fotoAntiga) && file_exists(__DIR__ . '/../' . $fotoAntiga)) {
                            unlink(__DIR__ . '/../' . $fotoAntiga);
                        }
                        $usuario->setFotoPerfilPath('uploads/usuarios/' . $newFileName);
                    }
                }
            }

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

    public function anunciante() {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($id <= 0) {
            header("Location: index.php");
            exit;
        }

        $usuario = $this->usuarioDAO->readById($id);
        if (!$usuario) {
            header("Location: index.php");
            exit;
        }

        $igreja = null;
        if ($usuario->getIgrejaId() > 0) {
            require_once __DIR__ . '/../dao/IgrejaDAO.php';
            $igrejaDAO = new IgrejaDAO();
            $igreja = $igrejaDAO->readOne($usuario->getIgrejaId());
        }

        require_once __DIR__ . '/../dao/AnuncioDAO.php';
        $anuncioDAO = new AnuncioDAO();
        $anuncios = $anuncioDAO->readActiveByUsuarioId($id);

        require_once __DIR__ . '/../views/usuarios/anunciante.php';
    }
}
