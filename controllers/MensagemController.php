<?php
require_once __DIR__ . '/../dao/MensagemDAO.php';
require_once __DIR__ . '/../dao/AnuncioDAO.php';

class MensagemController {
    private $mensagemDAO;
    private $anuncioDAO;

    public function __construct() {
        $this->mensagemDAO = new MensagemDAO();
        $this->anuncioDAO = new AnuncioDAO();
    }

    public function enviar() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['usuario_id'])) {
            echo json_encode(['success' => false, 'error' => 'Not logged in']);
            return;
        }

        $dados = json_decode(file_get_contents('php://input'), true);
        if (!$dados) {
            $dados = $_POST;
        }

        $anuncioId = (int)($dados['anuncioId'] ?? 0);
        $texto = trim($dados['texto'] ?? '');
        $destinatarioUsuarioId = (int)($dados['destinatarioUsuarioId'] ?? 0);
        $remetenteUsuarioId = $_SESSION['usuario_id'];

        if ($anuncioId <= 0 || empty($texto) || $destinatarioUsuarioId <= 0) {
            echo json_encode(['success' => false, 'error' => 'Invalid data']);
            return;
        }

        $mensagem = new Mensagem(0, $remetenteUsuarioId, $destinatarioUsuarioId, $anuncioId, $texto, new DateTime());
        
        if ($this->mensagemDAO->create($mensagem)) {
            echo json_encode([
                'success' => true, 
                'mensagem' => [
                    'id' => $mensagem->getId(),
                    'texto' => htmlspecialchars($mensagem->getTexto()),
                    'dataEnvio' => $mensagem->getDataEnvio()->format('d/m/Y H:i'),
                    'remetenteUsuarioId' => $mensagem->getRemetenteUsuarioId()
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to send message']);
        }
    }

    public function listar() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['usuario_id'])) {
            echo json_encode(['success' => false, 'error' => 'Not logged in']);
            return;
        }

        $anuncioId = isset($_GET['anuncioId']) ? (int)$_GET['anuncioId'] : 0;
        $outroUsuarioId = isset($_GET['outroUsuarioId']) ? (int)$_GET['outroUsuarioId'] : 0;
        $usuarioLogadoId = $_SESSION['usuario_id'];

        if ($anuncioId <= 0 || $outroUsuarioId <= 0) {
            echo json_encode(['success' => false, 'error' => 'Invalid parameters']);
            return;
        }

        $mensagens = $this->mensagemDAO->readChat($usuarioLogadoId, $outroUsuarioId, $anuncioId);
        $result = [];
        foreach ($mensagens as $msg) {
            $result[] = [
                'id' => $msg->getId(),
                'texto' => htmlspecialchars($msg->getTexto()),
                'data_envio' => $msg->getDataEnvio()->format('d/m/Y H:i'),
                'remetente_usuario_id' => $msg->getRemetenteUsuarioId()
            ];
        }

        echo json_encode(['success' => true, 'mensagens' => $result]);
    }

    public function listarChats() {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        

        require_once __DIR__ . '/../views/chats/chats.php';
    }


    public function apiListarChats() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['usuario_id'])) {
            echo json_encode(['success' => false, 'error' => 'Not logged in']);
            return;
        }

        $usuarioId = $_SESSION['usuario_id'];
        $chats = $this->mensagemDAO->readChats($usuarioId);
        $result = [];

        foreach ($chats as $chat) {
            $result[] = [
                'usuarioId' => $chat['outro_usuario_id'],
                'nomeUsuario' => $chat['nome_usuario'],
                'anuncioId' => $chat['anuncio_id'],
                'tituloAnuncio' => $chat['titulo_anuncio'],
                'ultimaMensagem' => $chat['ultima_mensagem'],
                'dataEnvio' => date('d/m/Y H:i', strtotime($chat['data_envio']))
            ];
        }

        echo json_encode(['success' => true, 'chats' => $result]);
    }
}
