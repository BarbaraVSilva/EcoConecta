<?php
session_start();
require_once '../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    if (empty($email) || empty($senha)) {
        $_SESSION['erro_login'] = "Por favor, preencha todos os campos.";
        header("Location: ../login.php");
        exit;
    }

    try {
        $stmt = $conn->prepare("SELECT id, nome, email, senha_hash, tipo_perfil FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();

        if ($usuario && password_verify($senha, $usuario['senha_hash'])) {
            $_SESSION['usuario_id']    = $usuario['id'];
            $_SESSION['usuario_nome']  = $usuario['nome'];
            $_SESSION['usuario_email'] = $usuario['email'];
            $_SESSION['tipo_perfil']   = $usuario['tipo_perfil'];
            $_SESSION['flash_sucesso'] = "Bem-vindo(a) de volta, " . htmlspecialchars($usuario['nome']) . "! 🌿";

            // Redireciona por tipo de perfil
            if ($usuario['tipo_perfil'] === 'empresa') {
                header("Location: ../perfil.php");
            } else {
                header("Location: ../dashboard.php");
            }
            exit;
        } else {
            $_SESSION['erro_login'] = "E-mail ou senha incorretos.";
            header("Location: ../login.php");
            exit;
        }

    } catch (Exception $e) {
        $_SESSION['erro_login'] = "Erro interno no servidor. Tente novamente.";
        header("Location: ../login.php");
        exit;
    }
} else {
    header("Location: ../login.php");
    exit;
}
?>
