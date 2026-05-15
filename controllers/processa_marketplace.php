<?php
session_start();
require_once '../config/conexao.php';

if (!isset($_SESSION['usuario_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../marketplace.php");
    exit;
}

$titulo = trim($_POST['titulo']);
$tipo = $_POST['tipo'];
$preco = floatval($_POST['preco']);
$descricao = trim($_POST['descricao']);
$criador_id = $_SESSION['usuario_id'];
$nome_imagem = null;

// Processa upload da imagem
if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    $extensao = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
    $permitidos = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    if (in_array($extensao, $permitidos)) {
        $nome_imagem = uniqid() . '.' . $extensao;
        $destino = '../uploads/marketplace/' . $nome_imagem;
        move_uploaded_file($_FILES['imagem']['tmp_name'], $destino);
    }
}

try {
    $stmt = $conn->prepare("INSERT INTO marketplace (titulo, descricao, tipo_anuncio, preco, imagem, criador_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssdsi", $titulo, $descricao, $tipo, $preco, $nome_imagem, $criador_id);
    $stmt->execute();
    
    header("Location: ../marketplace.php");
    exit;
} catch (Exception $e) {
    die("Erro ao salvar: " . $e->getMessage());
}
?>
