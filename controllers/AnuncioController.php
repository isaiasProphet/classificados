<?php
require_once __DIR__ . '/../dao/AnuncioDAO.php';
require_once __DIR__ . '/../dao/CategoriaDAO.php';
require_once __DIR__ . '/../dao/ImagemAnuncioDAO.php';

class AnuncioController {
    private $anuncioDAO;
    private $categoriaDAO;
    private $imagemDAO;

    // Diretório para uploads de imagens
    private const UPLOAD_DIR = __DIR__ . '/../uploads/anuncios/';
    private const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    private const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB
    private const MAX_IMAGES = 8;

    public function __construct() {
        $this->anuncioDAO = new AnuncioDAO();
        $this->categoriaDAO = new CategoriaDAO();
        $this->imagemDAO = new ImagemAnuncioDAO();
    }

    public function index() {
        $anuncios = $this->anuncioDAO->readAll();
        require_once __DIR__ . '/../views/home.php';
    }

    public function create() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login&error=not_logged_in");
            exit;
        }
        $categorias = $this->categoriaDAO->readAll();
        require_once __DIR__ . '/../views/anuncios/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['usuario_id'])) {
                header("Location: index.php?action=login&error=not_logged_in");
                exit;
            }

            $titulo = $_POST['titulo'] ?? '';
            $descricao = $_POST['descricao'] ?? '';
            $preco = (float) ($_POST['preco'] ?? 0);
            $subCategoriaId = (int) ($_POST['subCategoriaId'] ?? 0);
            
            // Usando o ID do usuário logado na sessão
            $usuarioId = $_SESSION['usuario_id']; 

            $anuncio = new Anuncio($titulo, $descricao, $subCategoriaId, $usuarioId, $preco, StatusAnuncio::PENDENTE_APROVACAO);
            
            if ($this->anuncioDAO->create($anuncio)) {
                $anuncioId = $anuncio->getId();

                // Processar upload de imagens
                $this->processarImagens($anuncioId);

                header("Location: index.php");
                exit;
            } else {
                echo "Erro ao salvar anúncio.";
            }
        }
    }

    public function show() {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        
        if ($id <= 0) {
            header("Location: index.php");
            exit;
        }

        $anuncio = $this->anuncioDAO->readById($id);
        
        if (!$anuncio) {
            header("Location: index.php");
            exit;
        }

        $imagens = $this->imagemDAO->findByAnuncioId($id);

        require_once __DIR__ . '/../views/anuncios/show.php';
    }

    /**
     * Processa o upload de múltiplas imagens para um anúncio.
     * Define a imagem principal (capa) com base no campo imgPrincipal enviado pelo formulário.
     */
    private function processarImagens(int $anuncioId): void {
        if (!isset($_FILES['imagens']) || empty($_FILES['imagens']['name'][0])) {
            return;
        }

        // Garantir que o diretório de upload existe
        $uploadDir = self::UPLOAD_DIR . $anuncioId . '/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $imgPrincipalIndex = isset($_POST['imgPrincipal']) ? (int) $_POST['imgPrincipal'] : 0;
        $filesCount = count($_FILES['imagens']['name']);
        $uploadedCount = 0;

        for ($i = 0; $i < $filesCount && $uploadedCount < self::MAX_IMAGES; $i++) {
            // Validar se o arquivo existe e não houve erro
            if ($_FILES['imagens']['error'][$i] !== UPLOAD_ERR_OK) {
                continue;
            }

            $tmpName = $_FILES['imagens']['tmp_name'][$i];
            $originalName = $_FILES['imagens']['name'][$i];
            $fileSize = $_FILES['imagens']['size'][$i];
            $fileType = $_FILES['imagens']['type'][$i];

            // Validar tipo do arquivo
            if (!in_array($fileType, self::ALLOWED_TYPES)) {
                continue;
            }

            // Validar tamanho
            if ($fileSize > self::MAX_FILE_SIZE) {
                continue;
            }

            // Gerar nome único para o arquivo
            $extension = pathinfo($originalName, PATHINFO_EXTENSION);
            $uniqueName = uniqid('img_', true) . '.' . strtolower($extension);
            $destPath = $uploadDir . $uniqueName;

            if (move_uploaded_file($tmpName, $destPath)) {
                // Caminho relativo para salvar no banco
                $caminhoRelativo = 'uploads/anuncios/' . $anuncioId . '/' . $uniqueName;
                $isPrincipal = ($i === $imgPrincipalIndex);

                $imagemAnuncio = new ImagemAnuncio($anuncioId, $caminhoRelativo, $isPrincipal);
                $this->imagemDAO->create($imagemAnuncio);
                $uploadedCount++;
            }
        }
    }
}
