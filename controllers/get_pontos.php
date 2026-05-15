<?php
require_once '../config/conexao.php';
header('Content-Type: application/json');

try {
    $result = $conn->query("SELECT id, titulo, descricao, latitude, longitude, tipo, status FROM pontos_impacto ORDER BY tipo");
    $pontos = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($pontos);
} catch (Exception $e) {
    echo json_encode([]);
}
?>
