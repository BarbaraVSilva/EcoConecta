<?php
session_start();
require_once '../config/conexao.php';

if (!isset($_SESSION['usuario_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../eventos.php");
    exit;
}

$titulo = trim($_POST['titulo']);
$data_evento = $_POST['data_evento'];
$localizacao = trim($_POST['localizacao']);
$descricao = trim($_POST['descricao']);
$criador_id = $_SESSION['usuario_id'];

try {
    $stmt = $conn->prepare("INSERT INTO eventos (titulo, descricao, data_evento, localizacao, criador_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $titulo, $descricao, $data_evento, $localizacao, $criador_id);
    $stmt->execute();
    
    // Gamificação: criar um evento dá +20 EcoPontos
    $stmt2 = $conn->prepare("UPDATE usuarios SET eco_pontos = eco_pontos + 20 WHERE id = ?");
    $stmt2->bind_param("i", $criador_id);
    $stmt2->execute();

    header("Location: ../eventos.php");
    exit;
} catch (Exception $e) {
    die("Erro ao salvar: " . $e->getMessage());
}
?>
