<?php
session_start();
require_once '../config/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $horta_id = $_POST['horta_id'];
    $quantidade = $_POST['quantidade_kg'];
    $descricao = trim($_POST['descricao']);

    try {
        $stmt = $conn->prepare("INSERT INTO doacoes_alimentos (horta_id, quantidade_kg, descricao) VALUES (?, ?, ?)");
        $stmt->bind_param("ids", $horta_id, $quantidade, $descricao);
        $stmt->execute();

        // Ganha pontos por doar colheita
        $stmtPts = $conn->prepare("UPDATE usuarios SET eco_pontos = eco_pontos + 50 WHERE id = ?");
        $stmtPts->bind_param("i", $_SESSION['usuario_id']);
        $stmtPts->execute();

        header("Location: ../mapa.php?sucesso=doacao_registrada");
        exit;
    } catch (Exception $e) {
        header("Location: ../mapa.php?erro=erro_doacao");
        exit;
    }
} else {
    header("Location: ../mapa.php");
    exit;
}
?>
