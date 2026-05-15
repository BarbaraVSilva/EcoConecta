<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Impacto - EcoConecta</title>
    <link rel="stylesheet" href="css/style.css">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    
    <style>
        .map-hero {
            padding-top: 6rem;
            padding-bottom: 2rem;
            text-align: center;
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
                <a href="mapa.php" class="active">Mapa de Impacto</a>
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

    <div class="container map-hero">
        <h1>Mapa de <span>Impacto</span></h1>
        <p>Encontre hortas comunitárias, ecopontos, lojas sustentáveis e pontos de troca em São Paulo.</p>
        
        <!-- Legenda do Mapa -->
        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap; margin-top: 1.5rem; font-size: 0.9rem;">
            <span style="display:flex; align-items:center; gap:6px;"><span style="background:#F39C12; border-radius:50%; width:22px; height:22px; display:inline-flex; align-items:center; justify-content:center; font-size:12px;">♻️</span> Ecoponto</span>
            <span style="display:flex; align-items:center; gap:6px;"><span style="background:#2ECC71; border-radius:50%; width:22px; height:22px; display:inline-flex; align-items:center; justify-content:center; font-size:12px;">🌱</span> Horta</span>
            <span style="display:flex; align-items:center; gap:6px;"><span style="background:#9B59B6; border-radius:50%; width:22px; height:22px; display:inline-flex; align-items:center; justify-content:center; font-size:12px;">🛍️</span> Loja Sustentável</span>
            <span style="display:flex; align-items:center; gap:6px;"><span style="background:#3498DB; border-radius:50%; width:22px; height:22px; display:inline-flex; align-items:center; justify-content:center; font-size:12px;">🔄</span> Ponto de Troca</span>
        </div>
        <div style="margin-top: 2rem; display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
            <div style="position: relative; width: 100%; max-width: 400px;">
                <input type="text" id="address-search" class="form-control" placeholder="Digite seu endereço ou bairro...">
                <button onclick="buscarEndereco()" class="btn btn-primary" style="position: absolute; right: 5px; top: 5px; padding: 0.4rem 1rem; font-size: 0.8rem;">Buscar</button>
            </div>
            <button onclick="minhaLocalizacao()" class="btn btn-outline" title="Usar minha localização atual">📍 Perto de Mim</button>
        </div>

        <div style="margin-top: 2rem;">
            <div id="map-container"></div>
        </div>
    </div>

    <!-- Modal de Report -->
    <div id="modal-report" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="document.getElementById('modal-report').style.display='none'">&times;</span>
            <h2>Reportar Problema no Ponto</h2>
            <form action="controllers/processa_report.php" method="POST" style="margin-top: 1.5rem;">
                <input type="hidden" id="ponto_id" name="ponto_id" value="">
                <div class="form-group">
                    <label>Qual o problema?</label>
                    <select name="motivo" class="form-control" required>
                        <option value="cheio">Ponto de coleta está lotado</option>
                        <option value="desativado">O local não existe mais / foi desativado</option>
                        <option value="manutencao">Precisa de manutenção (sujo, quebrado)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Detalhes (Opcional)</label>
                    <textarea name="mensagem" class="form-control" rows="2"></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Enviar Report (+10 EcoPontos)</button>
            </form>
        </div>
    </div>

    <!-- Modal de Doação de Alimentos -->
    <div id="modal-doacao" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="document.getElementById('modal-doacao').style.display='none'">&times;</span>
            <h2>Registrar Doação de Colheita</h2>
            <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 1rem;">Informe a quantidade colhida nesta horta para doação solidária.</p>
            <form action="controllers/processa_doacao_alimento.php" method="POST">
                <input type="hidden" id="horta_id" name="horta_id" value="">
                <div class="form-group">
                    <label>Quantidade (em kg)</label>
                    <input type="number" step="0.1" name="quantidade_kg" class="form-control" placeholder="Ex: 5.5" required>
                </div>
                <div class="form-group">
                    <label>O que foi colhido? (Opcional)</label>
                    <textarea name="descricao" class="form-control" rows="2" placeholder="Ex: Alface, tomate e cenouras..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Registrar Doação (+50 EcoPontos)</button>
            </form>
        </div>
    </div>

    <!-- Variável JS para saber se está logado -->
    <script>
        const isLoggedIn = <?php echo isset($_SESSION['usuario_id']) ? 'true' : 'false'; ?>;
    </script>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="js/mapa.js"></script>
<script src="js/menu.js"></script>
</body>
</html>



