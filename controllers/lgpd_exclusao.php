<?php
session_start();
require_once '../config/conexao.php';

if (!isset($_SESSION['usuario_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../login.php");
    exit;
}

try {
    $usuario_id = $_SESSION['usuario_id'];

    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();

    session_unset();
    session_destroy();
    
    session_start();
    $_SESSION['sucesso_exclusao'] = "Sua conta e todos os dados vinculados foram excluídos com sucesso (Em conformidade com a LGPD).";
    
    header("Location: ../login.php");
    exit;

} catch (Exception $e) {
    header("Location: ../oportunidades.php");
    exit;
}
?>
