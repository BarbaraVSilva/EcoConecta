<?php
session_start();
require_once '../config/conexao.php';
require_once '../config/google_config.php';

// Função para validar o ID Token diretamente com o Google (sem bibliotecas externas pesadas)
function verifyGoogleToken($id_token) {
    $url = "https://oauth2.googleapis.com/tokeninfo?id_token=" . urlencode($id_token);
    
    // Tenta usar cURL para maior resiliência em servidores que desativam allow_url_fopen
    if (function_exists('curl_version')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Evita falhas locais de SSL no XAMPP
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($http_code === 200) {
            return json_decode($response, true);
        }
    }
    
    // Fallback para file_get_contents se cURL não estiver disponível
    $context = stream_context_create([
        "http" => [
            "ignore_errors" => true,
            "header" => "User-Agent: PHP\r\n"
        ],
        "ssl" => [
            "verify_peer" => false,
            "verify_peer_name" => false
        ]
    ]);
    
    $response = @file_get_contents($url, false, $context);
    if ($response !== false) {
        $data = json_decode($response, true);
        if (isset($data['aud'])) {
            return $data;
        }
    }
    
    return false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_token'])) {
    $id_token = $_POST['id_token'];
    
    // Dados extras vindos do formulário
    $tipo_perfil = isset($_POST['tipo_perfil']) ? $_POST['tipo_perfil'] : 'cidadao';
    $lgpd = isset($_POST['lgpd']) ? intval($_POST['lgpd']) : 1;

    $payload = verifyGoogleToken($id_token);

    if ($payload && isset($payload['email'])) {
        // Validação adicional de segurança (Audience must match Client ID)
        if ($payload['aud'] !== GOOGLE_CLIENT_ID) {
            $_SESSION['erro_login'] = "Erro de validação: cliente incorreto.";
            header("Location: ../login.php");
            exit;
        }

        $email = trim($payload['email']);
        $nome = trim($payload['name']);
        
        // Garante que o e-mail está verificado pelo Google
        if (!isset($payload['email_verified']) || ($payload['email_verified'] !== true && $payload['email_verified'] !== 'true')) {
            $_SESSION['erro_login'] = "Este e-mail do Google não está verificado.";
            header("Location: ../login.php");
            exit;
        }

        try {
            // Verifica se o usuário já existe
            $stmt = $conn->prepare("SELECT id, nome, tipo_perfil FROM usuarios WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $usuario = $result->fetch_assoc();

            if ($usuario) {
                // Usuário cadastrado: inicia sessão
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                
                header("Location: ../oportunidades.php");
                exit;
            } else {
                // Usuário NÃO cadastrado: cadastra automaticamente via Google
                // Cria uma senha aleatória para preencher a coluna obrigatória 'senha_hash'
                $senha_aleatoria = bin2hex(random_bytes(16));
                $senha_hash = password_hash($senha_aleatoria, PASSWORD_DEFAULT);

                $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha_hash, tipo_perfil, aceite_lgpd) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssi", $nome, $email, $senha_hash, $tipo_perfil, $lgpd);
                $stmt->execute();

                // Autentica
                $_SESSION['usuario_id'] = $conn->insert_id;
                $_SESSION['usuario_nome'] = $nome;

                header("Location: ../oportunidades.php");
                exit;
            }
        } catch (Exception $e) {
            $_SESSION['erro_login'] = "Erro interno no processamento do banco de dados.";
            header("Location: ../login.php");
            exit;
        }
    } else {
        $_SESSION['erro_login'] = "Token do Google inválido ou expirado.";
        header("Location: ../login.php");
        exit;
    }
} else {
    header("Location: ../login.php");
    exit;
}
?>
