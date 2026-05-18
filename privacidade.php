<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidade - EcoConecta</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .privacy-container {
            max-width: 900px;
            margin: 120px auto 60px;
            padding: 40px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            line-height: 1.8;
        }
        .privacy-header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 2px solid #e8f5e9;
        }
        .privacy-header h1 {
            color: #2e7d32;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        .privacy-section {
            margin-bottom: 35px;
        }
        .privacy-section h2 {
            color: #388e3c;
            border-bottom: 2px solid #e8f5e9;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .lgpd-badge {
            display: inline-block;
            background: #e8f5e9;
            color: #2e7d32;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.9rem;
            margin-top: 5px;
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

    <div class="privacy-container">
        <div class="privacy-header">
            <h1><i class="fas fa-shield-alt" style="color:#2e7d32;"></i> Política de Privacidade</h1>
            <span class="lgpd-badge">Conformidade com a LGPD — Lei nº 13.709/2018</span>
            <p style="color:#666; margin-top:10px;">Última atualização: 15 de Maio de 2026</p>
        </div>

        <div class="privacy-section">
            <h2>1. Controlador dos Dados</h2>
            <p>O responsável pelo tratamento de seus dados pessoais é a plataforma <strong>EcoConecta</strong>, desenvolvida por Barbara. Para dúvidas, entre em contato pelo e-mail: <a href="mailto:privacidade@ecoconecta.com.br" style="color:#2e7d32;">privacidade@ecoconecta.com.br</a>.</p>
        </div>

        <div class="privacy-section">
            <h2>2. Dados Coletados</h2>
            <p>Coletamos as seguintes categorias de dados:</p>
            <ul>
                <li><strong>Dados de cadastro:</strong> nome, e-mail e tipo de perfil;</li>
                <li><strong>Dados de uso:</strong> pontos adicionados ao mapa, participação em eventos;</li>
                <li><strong>Dados técnicos:</strong> endereço IP, tipo de navegador e cookies de sessão.</li>
            </ul>
        </div>

        <div class="privacy-section">
            <h2>3. Finalidade do Tratamento</h2>
            <p>Seus dados são utilizados para:</p>
            <ul>
                <li>Autenticar e personalizar sua experiência na plataforma;</li>
                <li>Exibir pontos de impacto e eventos no mapa;</li>
                <li>Facilitar a comunicação no marketplace de upcycling e trocas;</li>
                <li>Enviar comunicações sobre oportunidades de voluntariado (apenas com consentimento).</li>
            </ul>
        </div>

        <div class="privacy-section">
            <h2>4. Base Legal (LGPD)</h2>
            <p>O tratamento de seus dados é fundamentado nas seguintes bases legais (Art. 7º da LGPD):</p>
            <ul>
                <li><strong>Consentimento:</strong> para cadastro e recebimento de comunicações;</li>
                <li><strong>Execução de contrato:</strong> para viabilizar as funcionalidades da plataforma;</li>
                <li><strong>Interesse legítimo:</strong> para análise de uso e segurança da plataforma.</li>
            </ul>
        </div>

        <div class="privacy-section">
            <h2>5. Compartilhamento de Dados</h2>
            <p>Não vendemos seus dados pessoais. Podemos compartilhar informações com terceiros prestadores de serviços (ex: hospedagem e análise de tráfego) exclusivamente para operação da plataforma, sempre sob rigorosos contratos de confidencialidade alinhados à LGPD.</p>
        </div>

        <div class="privacy-section">
            <h2>6. Seus Direitos como Titular (Art. 18 da LGPD)</h2>
            <p>Você tem os seguintes direitos, exercíveis a qualquer momento:</p>
            <ul>
                <li>Confirmar a existência de tratamento de dados pessoais;</li>
                <li>Acessar, corrigir ou atualizar seus dados;</li>
                <li>Solicitar a anonimização, bloqueio ou eliminação de dados desnecessários;</li>
                <li>Revogar seu consentimento a qualquer momento;</li>
                <li>Solicitar a portabilidade de seus dados a outro fornecedor.</li>
            </ul>
            <p>Para exercer seus direitos, use a função <strong>"Excluir Minha Conta"</strong> no perfil ou entre em contato pelo e-mail de privacidade.</p>
        </div>

        <div class="privacy-section">
            <h2>7. Segurança</h2>
            <p>Adotamos medidas técnicas e administrativas para proteger seus dados, incluindo hashing de senhas (bcrypt), sessões seguras e acesso restrito ao banco de dados.</p>
        </div>

        <div class="privacy-section">
            <h2>8. Cookies</h2>
            <p>Utilizamos cookies de sessão estritamente necessários para o funcionamento da plataforma. Também utilizamos o Microsoft Clarity para análise de comportamento do usuário de forma anonimizada. Você pode gerenciar cookies pelo seu navegador.</p>
        </div>

        <div class="privacy-section">
            <h2>9. Retenção de Dados</h2>
            <p>Seus dados são mantidos enquanto sua conta estiver ativa. Após a exclusão da conta, os dados pessoais são eliminados em até 30 dias, salvo obrigações legais que exijam retenção por prazo maior.</p>
        </div>

        <div class="privacy-section">
            <h2>10. Contato do DPO</h2>
            <p>Nosso Encarregado de Proteção de Dados (DPO) pode ser contatado em: <a href="mailto:privacidade@ecoconecta.com.br" style="color:#2e7d32;">privacidade@ecoconecta.com.br</a>. Responderemos em até 15 dias úteis.</p>
        </div>

        <div style="text-align: center; margin-top: 40px;">
            <a href="index.php" class="btn btn-primary"><i class="fas fa-home"></i> Voltar para o Início</a>
            <a href="termos.php" class="btn btn-outline" style="margin-left: 10px;"><i class="fas fa-file-contract"></i> Ver Termos de Uso</a>
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
