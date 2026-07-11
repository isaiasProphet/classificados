<?php
require_once __DIR__ . '/config/Database.php';

$conn = Database::getConnection();
$usuarioId = 1;
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

$stmt = $conn->prepare($query);
$stmt->bindValue(':usuarioId', $usuarioId, PDO::PARAM_INT);
$stmt->execute();
print_r($stmt->fetchAll());
