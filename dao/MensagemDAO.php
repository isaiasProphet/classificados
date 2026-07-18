<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/Mensagem.php';

class MensagemDAO {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function create(Mensagem $mensagem) {
        $query = "INSERT INTO Mensagem (remetente_usuario_id, destinatario_usuario_id, anuncio_id, texto, data_envio, lida, data_leitura) 
                  VALUES (:remetenteUsuarioId, :destinatarioUsuarioId, :anuncioId, :texto, :dataEnvio, :lida, :dataLeitura)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindValue(':remetenteUsuarioId', $mensagem->getRemetenteUsuarioId());
        $stmt->bindValue(':destinatarioUsuarioId', $mensagem->getDestinatarioUsuarioId());
        $stmt->bindValue(':anuncioId', $mensagem->getAnuncioId());
        $stmt->bindValue(':texto', $mensagem->getTexto());
        $stmt->bindValue(':dataEnvio', $mensagem->getDataEnvio()->format('Y-m-d H:i:s'));
        $stmt->bindValue(':lida', 0);
        $stmt->bindValue(':dataLeitura', null);

        if ($stmt->execute()) {
            $mensagem->setId($this->conn->lastInsertId());
            return true;
        }
        return false;
    }

    // Busca todas as mensagens de uma conversa (entre 2 usuarios sobre 1 anuncio)
    // Retorna array associativo com nome do remetente incluso
    public function readChat($usuarioLogadoId, $outroUsuarioId, $anuncioId) {
        $query = "SELECT m.*, u.nome AS nome_remetente
                  FROM Mensagem m
                  JOIN Usuario u ON u.id = m.remetente_usuario_id
                  WHERE m.anuncio_id = :anuncioId 
                  AND ((m.remetente_usuario_id = :u1 AND m.destinatario_usuario_id = :u2) 
                       OR (m.remetente_usuario_id = :u2 AND m.destinatario_usuario_id = :u1))
                  ORDER BY m.data_envio ASC";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':anuncioId', $anuncioId);
        $stmt->bindValue(':u1', $usuarioLogadoId);
        $stmt->bindValue(':u2', $outroUsuarioId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * Conta mensagens não lidas para o usuário destinatário.
     */
    public function countUnread(int $usuarioId): int {
        $query = "SELECT COUNT(*) FROM Mensagem WHERE destinatario_usuario_id = :usuarioId AND lida = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':usuarioId', $usuarioId, PDO::PARAM_INT);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function readChats($usuarioId) {
        $query = "SELECT 
                    sub.anuncio_id,
                    sub.outro_usuario_id,
                    MAX(sub.data_envio) AS data_envio,
                    a.titulo AS titulo_anuncio,
                    u.nome AS nome_usuario,
                    (SELECT texto FROM Mensagem m3 WHERE m3.anuncio_id = sub.anuncio_id AND 
                        ((m3.remetente_usuario_id = :usuarioId AND m3.destinatario_usuario_id = sub.outro_usuario_id) OR (m3.remetente_usuario_id = sub.outro_usuario_id AND m3.destinatario_usuario_id = :usuarioId))
                     ORDER BY m3.data_envio DESC LIMIT 1) AS ultima_mensagem
                FROM (
                    SELECT 
                        anuncio_id,
                        CASE 
                            WHEN remetente_usuario_id = :usuarioId THEN destinatario_usuario_id 
                            ELSE remetente_usuario_id 
                        END AS outro_usuario_id,
                        data_envio
                    FROM Mensagem
                    WHERE remetente_usuario_id = :usuarioId OR destinatario_usuario_id = :usuarioId
                ) AS sub
                JOIN Anuncio a ON sub.anuncio_id = a.id
                JOIN Usuario u ON u.id = sub.outro_usuario_id
                GROUP BY sub.anuncio_id, sub.outro_usuario_id, a.titulo, u.nome
                ORDER BY data_envio DESC";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':usuarioId', $usuarioId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function markAsRead(int $destinatarioId, int $remetenteId, int $anuncioId): bool {
        $query = "UPDATE Mensagem 
                  SET lida = 1, data_leitura = NOW() 
                  WHERE destinatario_usuario_id = :destinatarioId 
                  AND remetente_usuario_id = :remetenteId 
                  AND anuncio_id = :anuncioId 
                  AND lida = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':destinatarioId', $destinatarioId, PDO::PARAM_INT);
        $stmt->bindValue(':remetenteId', $remetenteId, PDO::PARAM_INT);
        $stmt->bindValue(':anuncioId', $anuncioId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

