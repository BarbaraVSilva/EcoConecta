<?php 
session_start(); 
require_once 'config/google_config.php';

// Guard: se já logado, redireciona ao perfil
if (isset($_SESSION['usuario_id'])) {
    header("Location: perfil.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EcoConecta</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Google Identity Services Library -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <style>
        .auth-container {
            max-width: 400px;
            margin: 8rem auto 4rem;
        }
        /* Custom styling to align Google sign-in button with theme */
        .g_id_signin {
            width: 100%;
            display: flex;
            justify-content: center;
        }
    </style>
<script type="text/javascript">
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "wrp7gx295r");
</script>
<link rel="stylesheet" href="css/accessibility.css">
</head>
<body>
    <header role="banner" aria-label="Cabeçalho do site">
        <div class="nav-container">
            <a href="index.php" class="logo">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"></path></svg>
                Eco<span>Conecta</span>
            </a>
            <button class="hamburger" id="hamburger" aria-label="Menu"><span></span><span></span><span></span></button><nav class="nav-links" aria-label="Menu principal" id="nav-links">
                <a href="index.php">Início</a>
                <a href="mapa.php">Mapa de Impacto</a>
                <div class="dropdown">
                    <a href="javascript:void(0)">Comunidade ▾</a>
                    <div class="dropdown-content">
                        <a href="eventos.php">Mutirões</a>
                        <a href="marketplace.php">Loja & Trocas</a>
                        <a href="guia.php">Guia Educacional</a>
                        <a href="ecoloja.php">EcoLoja</a>
                        <a href="dashboard.php">Painel de Impacto</a>
                    </div>
                </div>
                <a href="oportunidades.php">Oportunidades</a>
                <a href="login.php" class="active">Entrar</a>
                <a href="cadastro.php" class="btn btn-primary">Cadastre-se</a>
            </nav>
        </div>
    </header>
<main id="conteudo-principal" tabindex="-1">

    <div class="container auth-container">
        <div class="glass-card">
            <h2 style="text-align: center; margin-bottom: 1.5rem;">Acesse sua Conta</h2>
            
            <?php
            if (isset($_SESSION['erro_login'])) {
                echo '<div style="color: red; margin-bottom: 1rem; text-align: center;">' . $_SESSION['erro_login'] . '</div>';
                unset($_SESSION['erro_login']);
            }
            if (isset($_SESSION['sucesso_exclusao'])) {
                echo '<div style="color: green; margin-bottom: 1rem; text-align: center;">' . $_SESSION['sucesso_exclusao'] . '</div>';
                unset($_SESSION['sucesso_exclusao']);
            }
            ?>

            <form action="controllers/processa_login.php" method="POST">
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Entrar</button>
            </form>

            <div style="display: flex; align-items: center; text-align: center; margin: 1.5rem 0;">
                <hr style="flex: 1; border: none; border-top: 1px solid #ddd; margin: 0 10px;">
                <span style="color: var(--gray); font-size: 0.9rem;">ou entre com</span>
                <hr style="flex: 1; border: none; border-top: 1px solid #ddd; margin: 0 10px;">
            </div>

            <!-- Botão Google Sign-In -->
            <div style="display: flex; justify-content: center; margin-bottom: 1rem;">
                <div id="g_id_onload"
                     data-client_id="<?php echo GOOGLE_CLIENT_ID; ?>"
                     data-context="signin"
                     data-ux_mode="popup"
                     data-callback="handleCredentialResponse"
                     data-auto_prompt="false">
                </div>
                <div class="g_id_signin"
                     data-type="standard"
                     data-shape="pill"
                     data-theme="outline"
                     data-text="signin_with"
                     data-size="large"
                     data-logo_alignment="left"
                     data-width="100%">
                </div>
            </div>
            
            <p style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem;">
                Ainda não tem conta? <a href="cadastro.php" style="color: var(--primary); font-weight: bold;">Cadastre-se</a>
            </p>
        </div>
    </div>

    <!-- Script Callback do Google -->
    <script>
        function handleCredentialResponse(response) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'controllers/processa_google.php';
            
            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = 'id_token';
            tokenInput.value = response.credential;
            form.appendChild(tokenInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    </script>
<script src="js/menu.js"></script>
<script src="js/accessibility.js"></script>
</body>
</html>



