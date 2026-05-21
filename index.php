<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoConecta - Sustentabilidade e Oportunidade</title>
    <meta name="description" content="Conectando cidadãos a oportunidades de sustentabilidade, coleta seletiva, hortas comunitárias e economia circular em São Paulo.">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* ===== SEÇÃO DE IMPACTO ===== */
        .impact-section {
            background: linear-gradient(135deg, #1b5e20 0%, #2e7d32 50%, #388e3c 100%);
            padding: 5rem 2rem;
            color: white;
            text-align: center;
        }
        .impact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            max-width: 1100px;
            margin: 3rem auto 0;
        }
        .impact-item {
            padding: 2rem 1rem;
            background: rgba(255,255,255,0.1);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            transition: transform 0.3s;
        }
        .impact-item:hover { transform: translateY(-8px); }
        .impact-number {
            font-size: 3rem;
            font-weight: 800;
            color: #a5d6a7;
            display: block;
            line-height: 1;
        }
        .impact-label {
            font-size: 0.95rem;
            opacity: 0.9;
            margin-top: 0.5rem;
        }

        /* ===== COMO FUNCIONA ===== */
        .how-section {
            padding: 5rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        .section-title {
            text-align: center;
            font-size: 2.2rem;
            margin-bottom: 0.8rem;
            color: var(--dark);
        }
        .section-subtitle {
            text-align: center;
            color: var(--gray);
            margin-bottom: 3rem;
            font-size: 1.1rem;
        }
        .section-title span { color: var(--primary); }
        .steps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 2rem;
        }
        .step-card {
            text-align: center;
            padding: 2.5rem 1.5rem;
            background: white;
            border-radius: 24px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.05);
            position: relative;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .step-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 16px 40px rgba(46,204,113,0.15);
        }
        .step-number {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 1.2rem;
            box-shadow: 0 6px 20px rgba(46,204,113,0.3);
        }
        .step-icon { font-size: 2.4rem; margin-bottom: 1rem; }
        .step-card h3 { margin-bottom: 0.8rem; color: var(--dark); font-size: 1.15rem; }
        .step-card p { color: var(--gray); font-size: 0.9rem; line-height: 1.6; }

        /* ===== CICLO ECOCONECTA ===== */
        .cycle-section {
            background: white;
            border-radius: 30px;
            margin: 0 2rem 4rem;
            padding: 4rem 2rem;
            box-shadow: 0 8px 40px rgba(0,0,0,0.04);
        }
        .cycle-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        .cycle-card {
            padding: 2rem;
            border-radius: 20px;
            background: #f8fffe;
            border-left: 5px solid var(--primary);
            transition: transform 0.3s;
        }
        .cycle-card:hover { transform: translateX(6px); }
        .cycle-card .icon { font-size: 2.2rem; margin-bottom: 0.8rem; }
        .cycle-card h3 { margin-bottom: 0.5rem; font-size: 1.1rem; color: var(--dark); }
        .cycle-card p { color: var(--gray); font-size: 0.9rem; line-height: 1.6; }

        /* ===== NOTÍCIAS ===== */
        .news-section {
            padding: 5rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        .news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        .news-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0,0,0,0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .news-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.1);
        }
        .news-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .news-img-placeholder {
            width: 100%;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
        }
        .news-body { padding: 1.5rem; }
        .news-tag {
            display: inline-block;
            background: rgba(46,204,113,0.1);
            color: var(--primary-dark);
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0.25rem 0.7rem;
            border-radius: 50px;
            margin-bottom: 0.8rem;
        }
        .news-body h3 {
            font-size: 1.05rem;
            color: var(--dark);
            margin-bottom: 0.6rem;
            line-height: 1.4;
        }
        .news-body p {
            font-size: 0.875rem;
            color: var(--gray);
            line-height: 1.6;
            margin-bottom: 1rem;
        }
        .news-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.8rem;
            color: var(--gray);
            border-top: 1px solid #f0f0f0;
            padding-top: 0.8rem;
        }
        .news-source { font-weight: 600; color: var(--primary-dark); }

        /* ===== DEPOIMENTOS ===== */
        .testimonials-section {
            background: linear-gradient(135deg, #e8f5e9 0%, #f1f8e9 100%);
            padding: 5rem 2rem;
        }
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 2rem auto 0;
        }
        .testimonial-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.06);
            position: relative;
        }
        .testimonial-card::before {
            content: '"';
            position: absolute;
            top: 1rem;
            left: 1.5rem;
            font-size: 5rem;
            color: var(--primary);
            opacity: 0.15;
            font-family: Georgia, serif;
            line-height: 1;
        }
        .testimonial-text {
            font-size: 0.95rem;
            color: #555;
            line-height: 1.7;
            margin-bottom: 1.5rem;
            font-style: italic;
        }
        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }
        .author-avatar {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1rem;
            flex-shrink: 0;
        }
        .author-info .name { font-weight: 700; color: var(--dark); font-size: 0.9rem; }
        .author-info .role { font-size: 0.8rem; color: var(--gray); }
        .stars { color: #f39c12; font-size: 0.85rem; margin-bottom: 0.3rem; }

        /* ===== PARCEIROS ===== */
        .partners-section {
            padding: 4rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }
        .partners-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1.5rem;
            margin-top: 2rem;
        }
        .partner-badge {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 16px;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            font-weight: 600;
            color: var(--dark);
            font-size: 0.95rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.04);
            transition: transform 0.3s, box-shadow 0.3s, border-color 0.3s;
        }
        .partner-badge:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(46,204,113,0.15);
            border-color: var(--primary);
        }
        .partner-badge .badge-icon { font-size: 1.5rem; }
        .partner-badge .badge-type {
            display: block;
            font-size: 0.72rem;
            color: var(--gray);
            font-weight: 400;
        }

        /* ===== CTA FINAL ===== */
        .cta-section {
            background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
            padding: 6rem 2rem;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        .cta-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -20%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
            border-radius: 50%;
        }
        .cta-section h2 { font-size: 2.8rem; margin-bottom: 1rem; }
        .cta-section p { font-size: 1.2rem; opacity: 0.9; margin-bottom: 2.5rem; max-width: 600px; margin-left: auto; margin-right: auto; }
        .cta-buttons { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
        .btn-white {
            background: white;
            color: #1b5e20;
            font-weight: 700;
            padding: 0.9rem 2.2rem;
            border-radius: 50px;
            border: none;
            cursor: pointer;
            transition: var(--transition);
            display: inline-block;
            font-size: 1rem;
        }
        .btn-white:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
        .btn-ghost {
            background: transparent;
            color: white;
            font-weight: 700;
            padding: 0.9rem 2.2rem;
            border-radius: 50px;
            border: 2px solid rgba(255,255,255,0.7);
            cursor: pointer;
            transition: var(--transition);
            display: inline-block;
            font-size: 1rem;
        }
        .btn-ghost:hover { background: rgba(255,255,255,0.15); border-color: white; }

        /* ===== SDG STRIP ===== */
        .sdg-strip {
            background: #f8fffe;
            border-top: 1px solid rgba(46,204,113,0.15);
            border-bottom: 1px solid rgba(46,204,113,0.15);
            padding: 2rem;
            text-align: center;
        }
        .sdg-strip p { color: var(--gray); font-size: 0.9rem; margin-bottom: 1rem; }
        .sdg-badges {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.8rem;
        }
        .sdg-badge {
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-size: 0.82rem;
            font-weight: 700;
            color: white;
        }
        .sdg-2  { background: #d3a029; }
        .sdg-11 { background: #fd9d24; }
        .sdg-12 { background: #bf8b2e; }
        .sdg-13 { background: #3f7e44; }
        .sdg-15 { background: #56c02b; }
        .sdg-17 { background: #19486a; }

        @media (max-width: 768px) {
            .impact-number { font-size: 2.2rem; }
            .cta-section h2 { font-size: 1.9rem; }
            .how-section, .news-section { padding: 3rem 1rem; }
            .cycle-section { margin: 0 0.5rem 2rem; padding: 2.5rem 1rem; }
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
            <button class="hamburger" id="hamburger" aria-label="Menu"><span></span><span></span><span></span></button>
            <nav class="nav-links" aria-label="Menu principal" id="nav-links">
                <a href="index.php" class="active">Início</a>
                <a href="mapa.php">Mapa de Impacto</a>
                <div class="dropdown">
                    <a href="javascript:void(0)">Comunidade ▾</a>
                    <div class="dropdown-content">
                        <a href="eventos.php">Mutirões</a>
                        <a href="marketplace.php">Loja &amp; Trocas</a>
                        <a href="guia.php">Guia Educacional</a>
                        <a href="ecoloja.php">EcoLoja</a>
                        <a href="dashboard.php">Painel de Impacto</a>
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
<main id="conteudo-principal" tabindex="-1">

    <!-- ===== HERO ===== -->
    <section class="hero">
        <div class="hero-content">
            <h1 class="hero-title">Recicle, Cultive e <span>Alimente Esperança</span></h1>
            <p class="hero-subtitle">Transformamos lixo em comércio, espaços degradados em hortas produtivas e colheitas em alimento para quem tem fome. Junte-se à revolução da economia circular e solidária de São Paulo.</p>
            <div class="hero-buttons">
                <a href="mapa.php" class="btn btn-primary"><i class="fas fa-map-marker-alt"></i> Explorar Mapa</a>
                <a href="marketplace.php" class="btn btn-outline"><i class="fas fa-store"></i> Ver Marketplace</a>
            </div>
        </div>
        <div class="hero-image">
            <img src="assets/img/hero-eco.jpg" alt="Pessoas cultivando hortas e reciclando"
                 onerror="this.src='https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?q=80&w=2013&auto=format&fit=crop';">
        </div>
    </section>

    <!-- ===== SDG STRIP ===== -->
    <div class="sdg-strip">
        <p><strong>Alinhado aos Objetivos de Desenvolvimento Sustentável da ONU (ODS)</strong></p>
        <div class="sdg-badges">
            <span class="sdg-badge sdg-2">ODS 2 · Fome Zero</span>
            <span class="sdg-badge sdg-11">ODS 11 · Cidades Sustentáveis</span>
            <span class="sdg-badge sdg-12">ODS 12 · Consumo Responsável</span>
            <span class="sdg-badge sdg-13">ODS 13 · Ação Climática</span>
            <span class="sdg-badge sdg-15">ODS 15 · Vida Terrestre</span>
            <span class="sdg-badge sdg-17">ODS 17 · Parcerias</span>
        </div>
    </div>

    <!-- ===== IMPACTO ===== -->
    <section class="impact-section">
        <h2 style="font-size:2.2rem;">Nosso Impacto em <span style="color:#a5d6a7;">Números</span></h2>
        <p style="opacity:0.85; margin-top:0.5rem;">Cada ação conta. Cada participação transforma.</p>
        <div class="impact-grid">
            <div class="impact-item">
                <span class="impact-number">+500kg</span>
                <p class="impact-label">🥗 Alimentos doados para comunidades em situação de insegurança alimentar</p>
            </div>
            <div class="impact-item">
                <span class="impact-number">+1,2t</span>
                <p class="impact-label">♻️ Materiais reciclados transformados em produtos de upcycling</p>
            </div>
            <div class="impact-item">
                <span class="impact-number">19</span>
                <p class="impact-label">📍 Pontos de impacto mapeados (hortas, ecopontos, lojas e feiras)</p>
            </div>
            <div class="impact-item">
                <span class="impact-number">9</span>
                <p class="impact-label">🌳 Mutirões realizados com plantio de árvores nativas e limpeza urbana</p>
            </div>
            <div class="impact-item">
                <span class="impact-number">150+</span>
                <p class="impact-label">👥 Voluntários e cidadãos engajados na plataforma</p>
            </div>
        </div>
    </section>

    <!-- ===== COMO FUNCIONA ===== -->
    <section class="how-section">
        <h2 class="section-title">Como <span>Funciona</span>?</h2>
        <p class="section-subtitle">Quatro passos simples para transformar sua cidade — e o planeta.</p>
        <div class="steps-grid">
            <div class="step-card">
                <div class="step-number">1</div>
                <div class="step-icon">📍</div>
                <h3>Explore o Mapa</h3>
                <p>Localize ecopontos, hortas comunitárias, lojas sustentáveis e pontos de troca de sementes perto de você.</p>
            </div>
            <div class="step-card">
                <div class="step-number">2</div>
                <div class="step-icon">🤝</div>
                <h3>Participe e Contribua</h3>
                <p>Engaje-se em mutirões de limpeza, oficinas de compostagem e plantios coletivos na sua vizinhança.</p>
            </div>
            <div class="step-card">
                <div class="step-number">3</div>
                <div class="step-icon">🌿</div>
                <h3>Acumule EcoPontos</h3>
                <p>Cada ação sustentável gera pontos. Troque seus EcoPontos por descontos e recompensas parceiras na EcoLoja.</p>
            </div>
            <div class="step-card">
                <div class="step-number">4</div>
                <div class="step-icon">🍲</div>
                <h3>Gere Impacto Real</h3>
                <p>As colheitas das hortas vão direto para cozinhas solidárias. Seus resíduos voltam como renda para artesãos locais.</p>
            </div>
        </div>
    </section>

    <!-- ===== CICLO ECOCONECTA ===== -->
    <div style="max-width:1200px; margin:0 auto; padding: 0 2rem;">
        <div class="cycle-section">
            <h2 class="section-title">O Ciclo <span>EcoConecta</span></h2>
            <p class="section-subtitle">Uma cadeia completa de impacto positivo que une cidadãos, empresas e comunidades.</p>
            <div class="cycle-grid">
                <div class="cycle-card">
                    <div class="icon">♻️</div>
                    <h3>Reciclagem &amp; Renda</h3>
                    <p>Seus resíduos não são lixo. Conectamos pontos de coleta a artesãos, transformando plástico e vidro em produtos únicos vendidos no marketplace.</p>
                </div>
                <div class="cycle-card">
                    <div class="icon">🏡</div>
                    <h3>Restauração Urbana</h3>
                    <p>Mapeamos espaços públicos degradados e organizamos mutirões para criar hortas e jardins urbanos que antes eram terrenos abandonados.</p>
                </div>
                <div class="cycle-card">
                    <div class="icon">🌱</div>
                    <h3>Agricultura Comunitária</h3>
                    <p>Capacitamos moradores para produzir alimentos saudáveis e orgânicos em pequenos espaços urbanos, fortalecendo a soberania alimentar.</p>
                </div>
                <div class="cycle-card">
                    <div class="icon">🍲</div>
                    <h3>Combate à Fome</h3>
                    <p>A terra que respira, alimenta. Toda a colheita das hortas comunitárias vai para projetos de distribuição de refeições solidárias.</p>
                </div>
                <div class="cycle-card">
                    <div class="icon">📚</div>
                    <h3>Educação Ambiental</h3>
                    <p>Guias práticos, quizzes e certificados sobre compostagem, reciclagem e consumo consciente transformam conhecimento em hábito.</p>
                </div>
                <div class="cycle-card">
                    <div class="icon">🏢</div>
                    <h3>ESG Empresarial</h3>
                    <p>Empresas patrocinam hortas, criam recompensas e publicam oportunidades verdes, construindo sua agenda de responsabilidade socioambiental.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== NOTÍCIAS DO MUNDO ===== -->
    <section class="news-section">
        <h2 class="section-title">🌍 Notícias do <span>Mundo Verde</span></h2>
        <p class="section-subtitle">O que está acontecendo no cenário global da sustentabilidade.</p>
        <div class="news-grid">

            <article class="news-card">
                <img src="assets/img/news_onu_plastic.png" alt="Tratado global de plásticos" class="news-img"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="news-img-placeholder" style="background:linear-gradient(135deg,#1565c0,#42a5f5); display:none;">🌊</div>
                <div class="news-body">
                    <span class="news-tag">🌐 ONU</span>
                    <h3>Tratado Global do Plástico: 175 países debatem fim da poluição plástica até 2040</h3>
                    <p>A ONU intensifica negociações para criar um instrumento juridicamente vinculante que cubra todo o ciclo de vida do plástico, do design ao descarte, incluindo metas obrigatórias de redução.</p>
                    <div class="news-footer">
                        <span class="news-source"><i class="fas fa-newspaper"></i> UNEP · 2025</span>
                        <span>🕐 Leitura: 3 min</span>
                    </div>
                </div>
            </article>

            <article class="news-card">
                <img src="assets/img/news_urban_gardens.png" alt="Hortas urbanas" class="news-img"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="news-img-placeholder" style="background:linear-gradient(135deg,#2e7d32,#81c784); display:none;">🌿</div>
                <div class="news-body">
                    <span class="news-tag">🌱 Agricultura Urbana</span>
                    <h3>Cidades que lideram: como Singapura e Detroit transformam telhados em fazendas verticais</h3>
                    <p>Megacidades ao redor do mundo estão adotando hortas verticais em prédios comerciais e residenciais. Singapura já produz 30% de seus vegetais folhosos dentro dos próprios limites urbanos.</p>
                    <div class="news-footer">
                        <span class="news-source"><i class="fas fa-newspaper"></i> Guardian · 2025</span>
                        <span>🕐 Leitura: 4 min</span>
                    </div>
                </div>
            </article>

            <article class="news-card">
                <div class="news-img-placeholder" style="background:linear-gradient(135deg,#f57f17,#ffca28);">⚡</div>
                <div class="news-body">
                    <span class="news-tag">☀️ Energia Limpa</span>
                    <h3>Brasil bate recorde: energia solar ultrapassa 50 GW e já abastece 11 milhões de casas</h3>
                    <p>O Brasil consolidou sua posição como líder em energia solar na América Latina. O crescimento de microgeração residencial cresceu 180% nos últimos 3 anos, impulsionando a democratização da energia limpa.</p>
                    <div class="news-footer">
                        <span class="news-source"><i class="fas fa-newspaper"></i> Agência Brasil · 2025</span>
                        <span>🕐 Leitura: 3 min</span>
                    </div>
                </div>
            </article>

            <article class="news-card">
                <div class="news-img-placeholder" style="background:linear-gradient(135deg,#6a1b9a,#ab47bc);">🏙️</div>
                <div class="news-body">
                    <span class="news-tag">🏙️ Economia Circular</span>
                    <h3>Amsterdam declara-se "cidade circular": proíbe produtos de plástico de uso único em todo o setor público</h3>
                    <p>A capital holandesa implementou legislação de vanguarda obrigando que 50% dos materiais utilizados em construções públicas sejam reciclados ou reutilizados, tornando-se referência global.</p>
                    <div class="news-footer">
                        <span class="news-source"><i class="fas fa-newspaper"></i> Reuters · 2025</span>
                        <span>🕐 Leitura: 4 min</span>
                    </div>
                </div>
            </article>

            <article class="news-card">
                <div class="news-img-placeholder" style="background:linear-gradient(135deg,#00695c,#26a69a);">🌊</div>
                <div class="news-body">
                    <span class="news-tag">🐋 Oceanos</span>
                    <h3>The Ocean Cleanup remove 10 milhões de kg de plástico dos oceanos e rios em 2025</h3>
                    <p>A organização holandesa atingiu sua maior marca histórica de coleta, utilizando barreiras autônomas em rios da Ásia e interceptores oceânicos no Pacífico Norte. Parte dos materiais coletados é convertida em produtos.</p>
                    <div class="news-footer">
                        <span class="news-source"><i class="fas fa-newspaper"></i> The Ocean Cleanup · 2025</span>
                        <span>🕐 Leitura: 3 min</span>
                    </div>
                </div>
            </article>

            <article class="news-card">
                <div class="news-img-placeholder" style="background:linear-gradient(135deg,#b71c1c,#ef5350);">🔥</div>
                <div class="news-body">
                    <span class="news-tag">🌡️ Clima</span>
                    <h3>COP30 no Brasil: Belém sediará a conferência climática mais importante da última década em novembro de 2025</h3>
                    <p>Com a COP30 marcada para Belém do Pará, o Brasil se torna o centro das negociações globais sobre novas metas de descarbonização e financiamento climático para países em desenvolvimento.</p>
                    <div class="news-footer">
                        <span class="news-source"><i class="fas fa-newspaper"></i> UNFCCC · 2025</span>
                        <span>🕐 Leitura: 5 min</span>
                    </div>
                </div>
            </article>

        </div>
    </section>

    <!-- ===== DEPOIMENTOS ===== -->
    <section class="testimonials-section">
        <h2 class="section-title">O que dizem <span>nossos usuários</span></h2>
        <p class="section-subtitle">Histórias reais de pessoas que transformaram sua relação com o meio ambiente.</p>
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <p class="testimonial-text">Encontrei a Horta das Corujas pelo mapa e virei voluntária fixa. Hoje aprendo a compostar, faço amizades e ajudo a alimentar famílias. A EcoConecta mudou minha rotina completamente!</p>
                <div class="testimonial-author">
                    <div class="author-avatar">BS</div>
                    <div class="author-info">
                        <div class="stars">★★★★★</div>
                        <span class="name">Bárbara Silva</span>
                        <span class="role">Voluntária — Vila Madalena, SP</span>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <p class="testimonial-text">Vendia produtos de upcycling em feiras, mas sem alcance. No marketplace da EcoConecta vendi minha primeira composteira em dois dias! A comunidade aqui valoriza o trabalho sustentável de verdade.</p>
                <div class="testimonial-author">
                    <div class="author-avatar">CE</div>
                    <div class="author-info">
                        <div class="stars">★★★★★</div>
                        <span class="name">Carlos Eduardo</span>
                        <span class="role">Artesão de Upcycling — Pinheiros, SP</span>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <p class="testimonial-text">Como empresa, conseguimos patrocinar uma horta comunitária e publicar vagas verdes para nosso time de gestão ambiental. A plataforma nos conectou com profissionais alinhados com nossa missão ESG.</p>
                <div class="testimonial-author">
                    <div class="author-avatar">EL</div>
                    <div class="author-info">
                        <div class="stars">★★★★★</div>
                        <span class="name">EcoLógica Soluções</span>
                        <span class="role">Empresa Parceira — São Paulo, SP</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== PARCEIROS ===== -->
    <section class="partners-section">
        <h2 class="section-title">Parceiros <span>que Acreditam</span></h2>
        <p class="section-subtitle">Organizações que constroem um futuro mais verde ao lado da EcoConecta.</p>
        <div class="partners-grid">
            <div class="partner-badge">
                <span class="badge-icon">🌿</span>
                <div>
                    <span>EcoLógica Soluções</span>
                    <span class="badge-type">Assessoria Ambiental</span>
                </div>
            </div>
            <div class="partner-badge">
                <span class="badge-icon">🌳</span>
                <div>
                    <span>Instituto Ipê</span>
                    <span class="badge-type">Agroecologia & ONG</span>
                </div>
            </div>
            <div class="partner-badge">
                <span class="badge-icon">♻️</span>
                <div>
                    <span>Coopere Centro</span>
                    <span class="badge-type">Cooperativa de Reciclagem</span>
                </div>
            </div>
            <div class="partner-badge">
                <span class="badge-icon">🏡</span>
                <div>
                    <span>Verde Urbano</span>
                    <span class="badge-type">Paisagismo Ecológico</span>
                </div>
            </div>
            <div class="partner-badge">
                <span class="badge-icon">🌻</span>
                <div>
                    <span>Horta das Corujas</span>
                    <span class="badge-type">Horta Comunitária</span>
                </div>
            </div>
            <div class="partner-badge">
                <span class="badge-icon">🛒</span>
                <div>
                    <span>Armazém do Campo</span>
                    <span class="badge-type">Orgânicos Certificados</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== CTA FINAL ===== -->
    <section class="cta-section">
        <h2>Pronto para fazer <br>a diferença?</h2>
        <p>Crie sua conta gratuita, explore o mapa de impacto e comece hoje mesmo a acumular EcoPontos enquanto transforma São Paulo.</p>
        <div class="cta-buttons">
            <a href="cadastro.php" class="btn-white"><i class="fas fa-leaf"></i> Criar Conta Grátis</a>
            <a href="sobre.php" class="btn-ghost"><i class="fas fa-info-circle"></i> Conhecer o Projeto</a>
        </div>
    </section>

    </main>
<footer role="contentinfo">
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
<script>
    /* Contador animado para os números de impacto */
    const counters = document.querySelectorAll('.impact-number');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animation = 'none';
                entry.target.style.opacity = '0';
                setTimeout(() => {
                    entry.target.style.transition = 'opacity 0.6s ease';
                    entry.target.style.opacity = '1';
                }, 100);
            }
        });
    }, { threshold: 0.3 });
    counters.forEach(c => observer.observe(c));
</script>
<script src="js/accessibility.js"></script>
</body>
</html>
