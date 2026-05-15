<?php
session_start();
require_once '../config/conexao.php';

// Verifica se está logado e se é empresa
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $tipo = $_POST['tipo'];
    $link_externo = trim($_POST['link_externo']);
    $descricao = trim($_POST['descricao']);
    $criador_id = $_SESSION['usuario_id'];

    if (empty($titulo) || empty($descricao) || empty($link_externo)) {
        header("Location: ../oportunidades.php?erro=preencha_todos_campos");
        exit;
    }

    try {
        $stmt = $conn->prepare("INSERT INTO oportunidades (titulo, descricao, tipo, link_externo, criador_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $titulo, $descricao, $tipo, $link_externo, $criador_id);
        $stmt->execute();

        header("Location: ../oportunidades.php?sucesso=vaga_publicada");
        exit;

    } catch (Exception $e) {
        header("Location: ../oportunidades.php?erro=erro_servidor");
        exit;
    }
} else {
    header("Location: ../oportunidades.php");
    exit;
}
?>
