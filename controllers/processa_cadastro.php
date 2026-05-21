<?php
session_start();
require_once '../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $tipo_perfil = $_POST['tipo_perfil'];
    $lgpd = isset($_POST['lgpd']) ? 1 : 0;

    if (empty($nome) || empty($email) || empty($senha) || !$lgpd) {
        $_SESSION['erro_cadastro'] = "Por favor, preencha todos os campos e aceite os termos da LGPD.";
        header("Location: ../cadastro.php");
        exit;
    }

    try {
        // Verifica se o email já existe
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->fetch_assoc()) {
            $_SESSION['erro_cadastro'] = "Este e-mail já está cadastrado.";
            header("Location: ../cadastro.php");
            exit;
        }

        // Criptografa a senha
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        // Insere o usuário
        $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha_hash, tipo_perfil, aceite_lgpd) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $nome, $email, $senha_hash, $tipo_perfil, $lgpd);
        $stmt->execute();

        // Autentica o usuário automaticamente após o cadastro
        $_SESSION['usuario_id']    = $conn->insert_id;
        $_SESSION['usuario_nome']  = $nome;
        $_SESSION['usuario_email'] = $email;
        $_SESSION['tipo_perfil']   = $tipo_perfil;
        $_SESSION['flash_sucesso'] = "Conta criada com sucesso! Bem-vindo(a), " . htmlspecialchars($nome) . "! Explore o mapa e comece sua jornada 🌿";

        if ($tipo_perfil === 'empresa') {
            header("Location: ../perfil.php");
        } else {
            header("Location: ../mapa.php");
        }
        exit;

    } catch (Exception $e) {
        $_SESSION['erro_cadastro'] = "Erro interno no servidor. Tente novamente.";
        header("Location: ../cadastro.php");
        exit;
    }
} else {
    header("Location: ../cadastro.php");
    exit;
}
?>
