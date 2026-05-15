<?php
session_start();
require_once 'config/conexao.php';

try {
    $result = $conn->query("SELECT * FROM guia_educacional ORDER BY data_criacao DESC");
    $artigos = $result->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    $artigos = [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guia Educacional - EcoConecta</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .page-header { padding-top: 8rem; padding-bottom: 2rem; text-align: center; }
        .guia-card { border-left: 5px solid var(--primary); padding: 1.5rem; background: var(--white); box-shadow: var(--shadow); border-radius: 0 15px 15px 0; margin-bottom: 1.5rem; }
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
                    <a href="javascript:void(0)" class="active">Comunidade ▾</a>
                    <div class="dropdown-content">
                        <a href="eventos.php">Mutirões</a>
                        <a href="marketplace.php">Loja & Trocas</a>
                        <a href="guia.php" style="color:var(--primary)!important; font-weight:bold;">Guia Educacional</a>
                    </div>
                </div>
                <a href="oportunidades.php">Oportunidades</a>
                <a href="sobre.php">Sobre</a>
                <?php if(isset($_SESSION['usuario_id'])): ?>
                    <a href="perfil.php" style="font-weight:bold; color:var(--primary);">Meu Perfil</a>
                    <a href="logout.php" class="btn btn-outline">Sair</a>
                <?php else: ?>
                    <a href="login.php">Entrar</a>
                    <a href="cadastro.php" class="btn btn-primary">Cadastre-se</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <div class="container page-header">
        <h1>Guia <span>Educacional</span></h1>
        <p>Aprenda práticas sustentáveis e leve a ecologia para o seu dia a dia.</p>
    </div>

    <div class="container" style="padding-top: 0; max-width: 800px;">
        <?php foreach ($artigos as $a): ?>
            <div class="guia-card">
                <span class="tag" style="background: var(--light); color: var(--dark);"><?php echo htmlspecialchars($a['categoria']); ?></span>
                <h2 style="margin: 0.5rem 0;"><?php echo htmlspecialchars($a['titulo']); ?></h2>
                <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 1rem;">Publicado em <?php echo date('d/m/Y', strtotime($a['data_criacao'])); ?></p>
                <div style="line-height: 1.8;">
                    <?php echo nl2br(htmlspecialchars($a['conteudo'])); ?>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if(empty($artigos)) echo "<p style='text-align:center;'>Nenhum artigo disponível.</p>"; ?>
    </div>
<script src="js/menu.js"></script>
</body>
</html>



