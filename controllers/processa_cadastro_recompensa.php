<?php
session_start();
require_once '../config/conexao.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_perfil'] !== 'empresa') {
    http_response_code(403);
    echo "Apenas empresas parceiras logadas podem cadastrar recompensas.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $custo_pontos = intval($_POST['custo_pontos']);
    $quantidade = intval($_POST['quantidade']);
    $descricao = trim($_POST['descricao']);
    $empresa_id = $_SESSION['usuario_id'];

    if (empty($titulo) || $custo_pontos <= 0 || $quantidade <= 0 || empty($descricao)) {
        echo "<script>alert('Todos os campos são obrigatórios e devem conter valores válidos.'); window.history.back();</script>";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO recompensas (titulo, custo_pontos, quantidade_disponivel, descricao, empresa_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("siisi", $titulo, $custo_pontos, $quantidade, $descricao, $empresa_id);

    if ($stmt->execute()) {
        header("Location: ../ecoloja.php");
        exit;
    } else {
        echo "<script>alert('Ocorreu um erro ao registrar a recompensa. Tente novamente.'); window.history.back();</script>";
        exit;
    }
} else {
    http_response_code(400);
    echo "Requisição inválida.";
}
?>
