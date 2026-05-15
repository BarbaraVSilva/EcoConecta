<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - EcoVocação</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .auth-container {
            max-width: 500px;
            margin: 8rem auto 4rem;
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
                Eco<span>Vocação</span>
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
            
            <p style="text-align: center; margin-top: 1rem; font-size: 0.9rem;">
                Já tem uma conta? <a href="login.php" style="color: var(--primary); font-weight: bold;">Faça Login</a>
            </p>
        </div>
    </div>
<script src="js/menu.js"></script>
</body>
</html>



