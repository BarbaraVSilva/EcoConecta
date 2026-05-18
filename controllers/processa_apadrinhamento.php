<?php
session_start();
require_once '../config/conexao.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_perfil'] !== 'empresa') {
    http_response_code(403);
    echo "Apenas empresas parceiras logadas podem apadrinhar hortas.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ponto_id'])) {
    $ponto_id = intval($_POST['ponto_id']);
    $empresa_id = $_SESSION['usuario_id'];

    // Verifica se o ponto é uma horta e se já não possui padrinho
    $stmt = $conn->prepare("SELECT id, empresa_padrinho_id FROM pontos_impacto WHERE id = ? AND tipo = 'horta'");
    $stmt->bind_param("i", $ponto_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if (!$result) {
        echo "Ponto de impacto inválido ou não é uma horta comunitária.";
        exit;
    }

    if ($result['empresa_padrinho_id'] !== null) {
        echo "Esta horta comunitária já está apadrinhada por outra empresa.";
        exit;
    }

    // Registra o padrinho
    $update = $conn->prepare("UPDATE pontos_impacto SET empresa_padrinho_id = ? WHERE id = ?");
    $update->bind_param("ii", $empresa_id, $ponto_id);
    
    if ($update->execute()) {
        echo "Parabéns! Sua empresa apadrinhou esta horta com sucesso. Seu apoio ajudará a mantê-la ativa!";
    } else {
        echo "Erro ao registrar o apadrinhamento. Tente novamente mais tarde.";
    }
} else {
    http_response_code(400);
    echo "Requisição inválida.";
}
?>
