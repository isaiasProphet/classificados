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

    public function bairrosList() {
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

        require_once __DIR__ . '/../views/admin/bairros_list.php';
    }

    public function usuariosList() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        require_once __DIR__ . '/../dao/UsuarioDAO.php';
        $usuarioDAO = new UsuarioDAO();
        $usuarioLogado = $usuarioDAO->readById($_SESSION['usuario_id']);
        if ($usuarioLogado && $usuarioLogado->getPermissoes() !== PermissaoUsuario::ADMIN) {
            header("Location: index.php?error=unauthorized");
            exit;
        }

        $usuarios = $usuarioDAO->readAll();

        require_once __DIR__ . '/../views/admin/usuarios_list.php';
    }


    public function usuarioEdit() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $usuarioDAO = new UsuarioDAO();
        $usuarioLogado = $usuarioDAO->readById($_SESSION['usuario_id']);
        if ($usuarioLogado && $usuarioLogado->getPermissoes() !== PermissaoUsuario::ADMIN) {
            header("Location: index.php?error=unauthorized");
            exit;
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php?action=admin_usuarios_list");
            exit;
        }

        $usuarioEditar = $usuarioDAO->readById($id);
        if (!$usuarioEditar) {
            header("Location: index.php?action=admin_usuarios_list&error=notfound");
            exit;
        }

        require_once __DIR__ . '/../dao/IgrejaDAO.php';
        $igrejaDAO = new IgrejaDAO();
        $igrejas = $igrejaDAO->readAll();

        require_once __DIR__ . '/../views/admin/usuario_edit.php';
    }

    public function usuarioUpdate() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $usuarioDAO = new UsuarioDAO();
        $usuarioLogado = $usuarioDAO->readById($_SESSION['usuario_id']);
        if ($usuarioLogado && $usuarioLogado->getPermissoes() !== PermissaoUsuario::ADMIN) {
            header("Location: index.php?error=unauthorized");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if (!$id) {
                header("Location: index.php?action=admin_usuarios_list");
                exit;
            }

            $usuarioEditar = $usuarioDAO->readById($id);
            if ($usuarioEditar) {
                $usuarioEditar->setNome(trim($_POST['nome'] ?? $usuarioEditar->getNome()));
                $usuarioEditar->setEmail(trim($_POST['email'] ?? $usuarioEditar->getEmail()));
                $usuarioEditar->setTelefone(trim($_POST['telefone'] ?? $usuarioEditar->getTelefone()));
                
                if (isset($_POST['permissoes'])) {
                    $usuarioEditar->setPermissoes(PermissaoUsuario::from($_POST['permissoes']));
                }

                if (isset($_POST['igreja_id'])) {
                    $usuarioEditar->setIgrejaId(intval($_POST['igreja_id']));
                }
                
                if (!empty($_POST['senha'])) {
                    $usuarioEditar->setSenha($_POST['senha']);
                }

                $usuarioDAO->update($usuarioEditar);
            }
        }

        header("Location: index.php?action=admin_usuarios_list&success=updated");
        exit;
    }

    public function usuarioDelete() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $usuarioDAO = new UsuarioDAO();
        $usuarioLogado = $usuarioDAO->readById($_SESSION['usuario_id']);
        if ($usuarioLogado && $usuarioLogado->getPermissoes() !== PermissaoUsuario::ADMIN) {
            header("Location: index.php?error=unauthorized");
            exit;
        }

        $id = $_GET['id'] ?? null;
        if ($id) {
            $usuarioDAO->deactivate($id);
        }

        header("Location: index.php?action=admin_usuarios_list&success=deleted");
        exit;
    }

    public function anunciosList() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        require_once __DIR__ . '/../dao/UsuarioDAO.php';
        $usuarioDAO = new UsuarioDAO();
        $usuarioLogado = $usuarioDAO->readById($_SESSION['usuario_id']);
        if ($usuarioLogado && $usuarioLogado->getPermissoes() !== PermissaoUsuario::ADMIN) {
            header("Location: index.php?error=unauthorized");
            exit;
        }

        require_once __DIR__ . '/../dao/AnuncioDAO.php';
        $anuncioDAO = new AnuncioDAO();
        $anuncios = $anuncioDAO->readAll();

        require_once __DIR__ . '/../views/admin/anuncios_list.php';
    }

    public function bairroAdd() {
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

        // Fetch all cities with their state abbreviation (UF)
        require_once __DIR__ . '/../config/Database.php';
        $conn = Database::getConnection();
        $stmt = $conn->query("SELECT c.id, c.nome, e.uf FROM cidade c JOIN estados e ON c.estado_id = e.id ORDER BY c.nome ASC");
        $cidades = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require_once __DIR__ . '/../views/admin/bairro_add.php';
    }

    public function bairroStore() {
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
            require_once __DIR__ . '/../dao/BairroDAO.php';
            require_once __DIR__ . '/../model/Bairro.php';
            
            $nome = trim($_POST['nome'] ?? '');
            $cidadeId = isset($_POST['cidadeId']) ? (int)$_POST['cidadeId'] : 0;

            if ($nome !== '' && $cidadeId > 0) {
                $bairroDAO = new BairroDAO();
                $bairro = new Bairro($nome, $cidadeId);
                $bairroDAO->create($bairro);
            }
        }

        header("Location: index.php?action=admin_bairros_list");
        exit;
    }
}
