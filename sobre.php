<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nós - EcoConecta</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .about-container {
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
        }
        .hero-section {
            text-align: center;
            padding: 60px 20px;
            background: linear-gradient(135deg, #2e7d32, #66bb6a);
            color: white;
            border-radius: 20px;
            margin-bottom: 50px;
        }
        .hero-section h1 {
            font-size: 3rem;
            margin-bottom: 20px;
        }
        .hero-section p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-bottom: 60px;
        }
        .stat-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-10px);
        }
        .stat-card i {
            font-size: 2.5rem;
            color: #2e7d32;
            margin-bottom: 20px;
        }
        .stat-card h3 {
            margin-bottom: 10px;
            color: #333;
        }
        .mission-vision-values {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 60px;
        }
        .mvv-card {
            background: #f1f8e9;
            padding: 40px;
            border-radius: 15px;
            border-left: 5px solid #2e7d32;
        }
        .mvv-card h2 {
            color: #2e7d32;
            margin-bottom: 20px;
        }
        .idealizer-section {
            background: white;
            padding: 50px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .idealizer-image {
            width: 200px;
            height: 200px;
            background: #e0e0e0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: #999;
            flex-shrink: 0;
        }
        .idealizer-info h2 {
            color: #333;
            margin-bottom: 10px;
        }
        .idealizer-info p {
            color: #666;
            font-style: italic;
        }
        @media (max-width: 768px) {
            .idealizer-section {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="about-container">
        <a href="index.php" style="color: #2e7d32; text-decoration: none; font-weight: bold;"><i class="fas fa-arrow-left"></i> Voltar</a>
        
        <section class="hero-section">
            <h1>EcoConecta</h1>
            <p>Conectando pessoas a um futuro mais verde e sustentável em São Paulo.</p>
        </section>

        <section class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-map-marker-alt"></i>
                <h3>Mapeamento Real</h3>
                <p>Localize ecopontos, hortas e lojas sustentáveis perto de você.</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-users"></i>
                <h3>Comunidade Ativa</h3>
                <p>Participe de mutirões, trocas de sementes e eventos colaborativos.</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-leaf"></i>
                <h3>Educação Verde</h3>
                <p>Aprenda com guias práticos sobre compostagem, reciclagem e agricultura urbana.</p>
            </div>
        </section>

        <section class="mission-vision-values">
            <div class="mvv-card">
                <h2><i class="fas fa-bullseye"></i> Missão</h2>
                <p>Democratizar o acesso a práticas sustentáveis e conectar cidadãos a recursos ecológicos na cidade de São Paulo, promovendo a regeneração urbana.</p>
            </div>
            <div class="mvv-card">
                <h2><i class="fas fa-eye"></i> Visão</h2>
                <p>Tornar-se o principal hub de sustentabilidade comunitária do Brasil, inspirando cidades mais resilientes e conscientes através da tecnologia.</p>
            </div>
            <div class="mvv-card">
                <h2><i class="fas fa-heart"></i> Valores</h2>
                <p>Transparência, Colaboração, Sustentabilidade Real, Respeito à Biodiversidade e Inovação Social.</p>
            </div>
        </section>

        <section class="idealizer-section">
            <div class="idealizer-image">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="idealizer-info">
                <h2>Sobre a Idealizadora</h2>
                <p><strong>Barbara</strong></p>
                <p>Barbara é uma entusiasta da sustentabilidade e tecnologia que acredita que a mudança começa na comunidade. O EcoConecta nasceu do desejo de unir as pontas soltas da gestão de resíduos e agricultura urbana em São Paulo, transformando informações dispersas em uma rede funcional de impacto positivo.</p>
            </div>
        </section>
    </div>

    <footer style="text-align: center; padding: 40px; color: #666;">
        &copy; 2026 EcoConecta - Desenvolvido com <i class="fas fa-heart" style="color: #e91e63;"></i> para um mundo melhor.
    </footer>
</body>
</html>
