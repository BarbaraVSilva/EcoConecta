<?php
require_once '../config/conexao.php';
header('Content-Type: application/json');

try {
    $result = $conn->query("
        SELECT p.id, p.titulo, p.descricao, p.latitude, p.longitude, p.tipo, p.status, p.empresa_padrinho_id, u.nome AS padrinho_nome 
        FROM pontos_impacto p 
        LEFT JOIN usuarios u ON p.empresa_padrinho_id = u.id 
        ORDER BY p.tipo
    ");
    $pontos = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($pontos);
} catch (Exception $e) {
    echo json_encode([]);
}
?>
