<?php
session_start();
require_once '../config/conexao.php';

if (!isset($_SESSION['usuario_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../mapa.php");
    exit;
}

$ponto_id = intval($_POST['ponto_id']);
$motivo = $_POST['motivo'];
$mensagem = trim($_POST['mensagem']);
$usuario_id = $_SESSION['usuario_id'];

try {
    $stmt = $conn->prepare("INSERT INTO mapa_reports (ponto_id, usuario_id, motivo, mensagem) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $ponto_id, $usuario_id, $motivo, $mensagem);
    $stmt->execute();
    
    // Gamificação: reportar um ponto dá +10 EcoPontos
    $stmt2 = $conn->prepare("UPDATE usuarios SET eco_pontos = eco_pontos + 10 WHERE id = ?");
    $stmt2->bind_param("i", $usuario_id);
    $stmt2->execute();

    header("Location: ../mapa.php");
    exit;
} catch (Exception $e) {
    die("Erro ao salvar o report: " . $e->getMessage());
}
?>
