<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoConecta - Sustentabilidade e Oportunidade</title>
    <meta name="description" content="Conectando cidadãos a oportunidades de sustentabilidade, coleta seletiva e hortas comunitárias.">
    <link rel="stylesheet" href="css/style.css">
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
                <a href="index.php" class="active">Início</a>
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

    <section class="hero">
        <div class="hero-content">
            <h1 class="hero-title">Recicle, Cultive e <span>Alimente Esperança</span></h1>
            <p class="hero-subtitle">Transformamos lixo em comércio, espaços degradados em hortas produtivas e colheitas em alimento para quem tem fome. Junte-se à revolução da economia circular e solidária.</p>
            <div class="hero-buttons">
                <a href="mapa.php" class="btn btn-primary">Explorar Mapa</a>
                <a href="marketplace.php" class="btn btn-outline">Ver Marketplace</a>
            </div>
        </div>
        <div class="hero-image">
            <img src="assets/img/hero-eco.jpg" alt="Pessoas cultivando hortas e reciclando" onerror="this.src='https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?q=80&w=2013&auto=format&fit=crop';">
        </div>
    </section>

    <section class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
        <div class="oportunidades-grid" style="text-align: center;">
            <div class="glass-card">
                <h2 style="color: var(--primary); font-size: 2.5rem;">+500kg</h2>
                <p>Alimentos doados para comunidades em situação de fome.</p>
            </div>
            <div class="glass-card">
                <h2 style="color: var(--secondary); font-size: 2.5rem;">+1.2t</h2>
                <p>Materiais reciclados transformados em produtos de upcycling.</p>
            </div>
            <div class="glass-card">
                <h2 style="color: var(--primary-dark); font-size: 2.5rem;">15</h2>
                <p>Espaços públicos degradados restaurados em jardins e hortas.</p>
            </div>
        </div>
    </section>

    <section class="container" style="background: white; border-radius: 30px; margin-bottom: 4rem; padding: 4rem 2rem;">
        <h2 style="text-align: center; margin-bottom: 3rem;">O Ciclo <span>EcoConecta</span></h2>
        <div class="oportunidades-grid">
            <div class="glass-card">
                <h3>♻️ Reciclagem & Renda</h3>
                <p>Seus resíduos não são lixo. Conectamos pontos de coleta a artesãos, transformando plástico e vidro em produtos únicos vendidos no nosso marketplace.</p>
            </div>
            <div class="glass-card">
                <h3>🏡 Restauração Urbana</h3>
                <p>Lutamos contra o abandono. Mapeamos espaços públicos degradados e organizamos mutirões para criar hortas e jardins urbanos.</p>
            </div>
            <div class="glass-card">
                <h3>🍲 Combate à Fome</h3>
                <p>A terra que respira, alimenta. Toda a colheita das nossas hortas comunitárias é destinada a projetos de distribuição de refeições solidárias.</p>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-content">
            <div class="logo" style="color: white;">Eco<span style="color: var(--primary);">Conecta</span></div>
            <p>&copy; 2026 EcoConecta. Todos os direitos reservados.</p>
            <div class="footer-links">
                <a href="sobre.php" style="margin-left: 1rem;">Sobre</a>
                <a href="termos.php" style="margin-left: 1rem;">Termos</a>
                <a href="privacidade.php" style="margin-left: 1rem;">Privacidade (LGPD)</a>
            </div>
        </div>
    </footer>
<script src="js/menu.js"></script>
</body>
</html>



