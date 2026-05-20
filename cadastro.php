<?php 
session_start(); 
require_once 'config/google_config.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - EcoConecta</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Google Identity Services Library -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <style>
        .auth-container {
            max-width: 500px;
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
</head>
<body>
    <header>
        <div class="nav-container">
            <a href="index.php" class="logo">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"></path></svg>
                Eco<span>Conecta</span>
            </a>
            <button class="hamburger" id="hamburger" aria-label="Menu"><span></span><span></span><span></span></button><nav class="nav-links" id="nav-links">
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
                <a href="login.php">Entrar</a>
                <a href="cadastro.php" class="btn btn-primary active">Cadastre-se</a>
            </nav>
        </div>
    </header>

    <div class="container auth-container">
        <div class="glass-card">
            <h2 style="text-align: center; margin-bottom: 1.5rem;">Crie sua Conta</h2>
            
            <?php
            if (isset($_SESSION['erro_cadastro'])) {
                echo '<div style="color: red; margin-bottom: 1rem; text-align: center;">' . $_SESSION['erro_cadastro'] . '</div>';
                unset($_SESSION['erro_cadastro']);
            }
            ?>

            <form action="controllers/processa_cadastro.php" method="POST">
                <div class="form-group">
                    <label for="nome">Nome Completo</label>
                    <input type="text" id="nome" name="nome" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="tipo_perfil">Perfil</label>
                    <select id="tipo_perfil" name="tipo_perfil" class="form-control">
                        <option value="cidadao">Cidadão (Voluntário/Buscando Vagas)</option>
                        <option value="empresa">Empresa/Organização</option>
                    </select>
                </div>
                
                <div class="form-group" style="display: flex; gap: 0.5rem; align-items: center;">
                    <input type="checkbox" id="lgpd" name="lgpd" required>
                    <label for="lgpd" style="margin-bottom: 0; font-size: 0.9rem; font-weight: normal;">Concordo com os Termos de Privacidade e LGPD</label>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Cadastrar</button>
            </form>

            <div style="display: flex; align-items: center; text-align: center; margin: 1.5rem 0;">
                <hr style="flex: 1; border: none; border-top: 1px solid #ddd; margin: 0 10px;">
                <span style="color: var(--gray); font-size: 0.9rem;">ou cadastre-se com</span>
                <hr style="flex: 1; border: none; border-top: 1px solid #ddd; margin: 0 10px;">
            </div>

            <!-- Botão Google Sign-In -->
            <div style="display: flex; justify-content: center; margin-bottom: 1rem;">
                <div id="g_id_onload"
                     data-client_id="<?php echo GOOGLE_CLIENT_ID; ?>"
                     data-context="signup"
                     data-ux_mode="popup"
                     data-callback="handleCredentialResponse"
                     data-auto_prompt="false">
                </div>
                <div class="g_id_signin"
                     data-type="standard"
                     data-shape="pill"
                     data-theme="outline"
                     data-text="signup_with"
                     data-size="large"
                     data-logo_alignment="left"
                     data-width="100%">
                </div>
            </div>
            
            <p style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem;">
                Já tem uma conta? <a href="login.php" style="color: var(--primary); font-weight: bold;">Faça Login</a>
            </p>
        </div>
    </div>

    <!-- Script Callback do Google -->
    <script>
        function handleCredentialResponse(response) {
            // Verifica se o checkbox da LGPD está marcado antes de submeter
            const lgpdCheckbox = document.getElementById('lgpd');
            if (lgpdCheckbox && !lgpdCheckbox.checked) {
                alert('Para se cadastrar, você precisa concordar com os Termos de Privacidade e LGPD.');
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'controllers/processa_google.php';
            
            // Token JWT retornado pelo Google
            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = 'id_token';
            tokenInput.value = response.credential;
            form.appendChild(tokenInput);

            // Perfil selecionado pelo usuário
            const perfilSelect = document.getElementById('tipo_perfil');
            if (perfilSelect) {
                const perfilInput = document.createElement('input');
                perfilInput.type = 'hidden';
                perfilInput.name = 'tipo_perfil';
                perfilInput.value = perfilSelect.value;
                form.appendChild(perfilInput);
            }

            // Confirmação do aceite da LGPD
            const lgpdInput = document.createElement('input');
            lgpdInput.type = 'hidden';
            lgpdInput.name = 'lgpd';
            lgpdInput.value = '1';
            form.appendChild(lgpdInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    </script>
<script src="js/menu.js"></script>
</body>
</html>
