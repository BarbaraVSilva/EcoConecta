<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termos de Uso - EcoConecta</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .terms-container {
            max-width: 900px;
            margin: 120px auto 60px;
            padding: 40px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            line-height: 1.8;
        }
        .terms-header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 2px solid #e8f5e9;
        }
        .terms-header h1 {
            color: #2e7d32;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        .terms-section {
            margin-bottom: 35px;
        }
        .terms-section h2 {
            color: #388e3c;
            border-bottom: 2px solid #e8f5e9;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <header>
        <div class="nav-container">
            <a href="index.php" class="logo">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"></path></svg>
                Eco<span>Conecta</span>
            </a>
            <button class="hamburger" id="hamburger" aria-label="Menu"><span></span><span></span><span></span></button>
            <nav class="nav-links" id="nav-links">
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

    <div class="terms-container">
        <div class="terms-header">
            <h1><i class="fas fa-file-contract" style="color:#2e7d32;"></i> Termos de Uso</h1>
            <p style="color:#666;">Última atualização: 15 de Maio de 2026</p>
        </div>

        <div class="terms-section">
            <h2>1. Aceitação dos Termos</h2>
            <p>Ao acessar e utilizar a plataforma EcoConecta, você concorda em cumprir e estar vinculado a estes Termos de Uso. Se você não concordar com qualquer parte destes termos, não deverá utilizar nossos serviços.</p>
        </div>

        <div class="terms-section">
            <h2>2. Descrição do Serviço</h2>
            <p>O EcoConecta é uma plataforma de conexão para práticas sustentáveis, incluindo mapeamento de pontos de descarte, hortas comunitárias, lojas sustentáveis, pontos de troca, eventos, marketplace de upcycling e oportunidades de voluntariado. Somos facilitadores de conexões e não nos responsabilizamos pelas interações diretas entre usuários.</p>
        </div>

        <div class="terms-section">
            <h2>3. Cadastro e Segurança</h2>
            <p>Para utilizar certas funcionalidades, é necessário criar uma conta. Você é responsável por manter a confidencialidade de sua senha e por todas as atividades que ocorrem em sua conta. Caso identifique uso não autorizado, comunique-nos imediatamente em privacidade@ecoconecta.com.br.</p>
        </div>

        <div class="terms-section">
            <h2>4. Conduta do Usuário</h2>
            <p>Você concorda em não utilizar a plataforma para postar conteúdo ilegal, ofensivo, difamatório, discriminatório ou que viole direitos de propriedade intelectual. Reservamo-nos o direito de remover qualquer conteúdo ou conta que viole estas regras, sem aviso prévio.</p>
        </div>

        <div class="terms-section">
            <h2>5. Marketplace e Trocas</h2>
            <p>As transações realizadas no marketplace são de inteira responsabilidade dos envolvidos. O EcoConecta não garante a qualidade dos itens anunciados nem se responsabiliza por prejuízos decorrentes de trocas ou vendas entre usuários.</p>
        </div>

        <div class="terms-section">
            <h2>6. Eventos e Mutirões</h2>
            <p>As informações sobre eventos são divulgadas de boa-fé. Alterações de data, local ou cancelamentos podem ocorrer a critério dos organizadores. O EcoConecta não se responsabiliza por danos decorrentes de tais mudanças.</p>
        </div>

        <div class="terms-section">
            <h2>7. Propriedade Intelectual</h2>
            <p>Todo o conteúdo presente na plataforma, incluindo logos, textos e design, é de propriedade do EcoConecta ou de seus licenciadores e está protegido por leis de direitos autorais. É proibida a reprodução sem autorização expressa.</p>
        </div>

        <div class="terms-section">
            <h2>8. Limitação de Responsabilidade</h2>
            <p>O EcoConecta não garante disponibilidade ininterrupta do serviço e não se responsabiliza por falhas técnicas, perda de dados ou danos indiretos decorrentes do uso da plataforma.</p>
        </div>

        <div class="terms-section">
            <h2>9. Alterações nos Termos</h2>
            <p>Podemos atualizar estes termos periodicamente. O uso continuado da plataforma após as alterações constitui sua aceitação dos novos termos. Alterações relevantes serão comunicadas por e-mail.</p>
        </div>

        <div class="terms-section">
            <h2>10. Foro</h2>
            <p>Estes termos são regidos pelas leis da República Federativa do Brasil. Fica eleito o foro da Comarca de São Paulo/SP para dirimir quaisquer questões oriundas deste documento, com renúncia a qualquer outro, por mais privilegiado que seja.</p>
        </div>

        <div style="text-align: center; margin-top: 40px;">
            <a href="index.php" class="btn btn-primary"><i class="fas fa-home"></i> Voltar para o Início</a>
            <a href="privacidade.php" class="btn btn-outline" style="margin-left: 10px;"><i class="fas fa-shield-alt"></i> Ver Política de Privacidade</a>
        </div>
    </div>

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
