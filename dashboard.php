<?php
session_start();
require_once 'config/conexao.php';

// 1. Total de Alimentos Doados (doacoes_alimentos)
$res_doacoes = $conn->query("SELECT SUM(quantidade_kg) AS total_kg, COUNT(id) AS total_registros FROM doacoes_alimentos");
$row_doacoes = $res_doacoes->fetch_assoc();
$total_kg = floatval($row_doacoes['total_kg']);
$num_doacoes = intval($row_doacoes['total_registros']);

// Cálculos ecológicos reais
$refeicoes_geradas = $total_kg * 4; // 1kg = 4 refeições saudáveis
$co2_compostagem = $total_kg * 0.8; // 1kg alimento compostado = 0.8kg CO2 evitado comparado a aterro

// 2. Itens de Upcycling Reciclados (marketplace)
$res_upcycling = $conn->query("SELECT COUNT(id) AS total_upcycling FROM marketplace WHERE tipo_anuncio = 'upcycling'");
$row_upcycling = $res_upcycling->fetch_assoc();
$total_upcycling = intval($row_upcycling['total_upcycling']);
$co2_upcycling = $total_upcycling * 1.5; // Cada produto reciclado economiza 1.5kg de CO2 em nova fabricação

// CO2 Total Evitado
$co2_total_salvo = $co2_compostagem + $co2_upcycling;

// 3. Pontos de Impacto Mapeados por Categoria
$res_pontos = $conn->query("SELECT tipo, COUNT(id) AS qtd FROM pontos_impacto GROUP BY tipo");
$pontos = ['coleta' => 0, 'horta' => 0, 'loja' => 0, 'troca' => 0];
while($row = $res_pontos->fetch_assoc()) {
    $pontos[$row['tipo']] = intval($row['qtd']);
}

// 4. Mutirões Realizados (eventos)
$res_eventos = $conn->query("SELECT COUNT(id) AS total FROM eventos");
$row_eventos = $res_eventos->fetch_assoc();
$total_eventos = intval($row_eventos['total']);

// 5. Cidadãos e Empresas Cadastrados
$res_usuarios = $conn->query("SELECT tipo_perfil, COUNT(id) AS qtd FROM usuarios GROUP BY tipo_perfil");
$perfis = ['cidadao' => 0, 'empresa' => 0];
while($row = $res_usuarios->fetch_assoc()) {
    $perfis[$row['tipo_perfil']] = intval($row['qtd']);
}
$total_usuarios = $perfis['cidadao'] + $perfis['empresa'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Impacto - EcoConecta</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .dashboard-container {
            padding-top: 6rem;
            padding-bottom: 4rem;
        }
        .dashboard-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        .dashboard-header h1 {
            font-size: 2.5rem;
            color: var(--dark);
        }
        .dashboard-header p {
            color: var(--gray);
            font-size: 1.1rem;
        }
        /* Grid de Destaques */
        .impact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 3.5rem;
        }
        .impact-card {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            gap: 25px;
            border: 1px solid rgba(46, 125, 50, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .impact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(46, 125, 50, 0.1);
        }
        .impact-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            flex-shrink: 0;
        }
        .impact-info h3 {
            font-size: 0.95rem;
            color: var(--gray);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .impact-info h2 {
            font-size: 1.8rem;
            color: var(--dark);
            font-weight: 700;
        }
        /* Seção Detalhada */
        .dashboard-details {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }
        @media (max-width: 992px) {
            .dashboard-details {
                grid-template-columns: 1fr;
            }
        }
        .detail-panel {
            background: white;
            padding: 35px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .detail-panel h2 {
            font-size: 1.4rem;
            color: var(--dark);
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .detail-panel h2 i {
            color: var(--primary);
        }
        /* Barras de Metas */
        .goal-bar-container {
            margin-bottom: 20px;
        }
        .goal-header {
            display: flex;
            justify-content: space-between;
            font-size: 0.95rem;
            margin-bottom: 8px;
            font-weight: 500;
        }
        .goal-bar-bg {
            background: #e8f5e9;
            height: 12px;
            border-radius: 6px;
            overflow: hidden;
            position: relative;
        }
        .goal-bar-fill {
            background: linear-gradient(90deg, #66bb6a, var(--primary));
            height: 100%;
            border-radius: 6px;
            transition: width 1s ease-in-out;
        }
        /* Lista de Lojas/Padrinhos */
        .sponsors-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .sponsor-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 12px;
            border: 1px solid rgba(0,0,0,0.02);
        }
        .sponsor-item i {
            font-size: 1.5rem;
            color: var(--primary);
        }
        .sponsor-item-info h4 {
            font-size: 0.95rem;
            color: var(--dark);
            margin-bottom: 2px;
        }
        .sponsor-item-info p {
            font-size: 0.8rem;
            color: var(--gray);
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
            <button class="hamburger" id="hamburger" aria-label="Menu"><span></span><span></span><span></span></button>
            <nav class="nav-links" id="nav-links">
                <a href="index.php">Início</a>
                <a href="mapa.php">Mapa de Impacto</a>
                <div class="dropdown open">
                    <a href="javascript:void(0)">Comunidade ▾</a>
                    <div class="dropdown-content">
                        <a href="eventos.php">Mutirões</a>
                        <a href="marketplace.php">Loja & Trocas</a>
                        <a href="guia.php">Guia Educacional</a>
                        <a href="ecoloja.php">EcoLoja</a>
                        <a href="dashboard.php" class="active">Painel de Impacto</a>
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

    <div class="container dashboard-container">
        <div class="dashboard-header">
            <h1>Painel de <span>Impacto Ambiental</span></h1>
            <p>Acompanhe em tempo real o resultado de nossas colheitas, reciclagens e parcerias ecológicas em São Paulo.</p>
        </div>

        <!-- Destaques de Impacto -->
        <div class="impact-grid">
            <div class="impact-card">
                <div class="impact-icon" style="background: #e8f5e9; color: #2e7d32;">
                    <i class="fas fa-apple-alt"></i>
                </div>
                <div class="impact-info">
                    <h3>Alimentos Doados</h3>
                    <h2><?php echo number_format($total_kg, 1, ',', '.'); ?> Kg</h2>
                </div>
            </div>
            
            <div class="impact-card">
                <div class="impact-icon" style="background: #e1f5fe; color: #0288d1;">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
                <div class="impact-info">
                    <h3>Refeições Saudáveis</h3>
                    <h2><?php echo number_format($refeicoes_geradas, 0, ',', '.'); ?> pratos</h2>
                </div>
            </div>
            
            <div class="impact-card">
                <div class="impact-icon" style="background: #efebe9; color: #5d4037;">
                    <i class="fas fa-cloud-sun"></i>
                </div>
                <div class="impact-info">
                    <h3>CO₂ Poupado</h3>
                    <h2><?php echo number_format($co2_total_salvo, 1, ',', '.'); ?> Kg</h2>
                </div>
            </div>

            <div class="impact-card">
                <div class="impact-icon" style="background: #fff8e1; color: #ffb300;">
                    <i class="fas fa-users"></i>
                </div>
                <div class="impact-info">
                    <h3>Voluntários Ativos</h3>
                    <h2><?php echo $total_usuarios; ?> agentes</h2>
                </div>
            </div>
        </div>

        <div class="dashboard-details">
            <!-- Painel Esquerdo: Metas da Comunidade -->
            <div class="detail-panel">
                <h2><i class="fas fa-bullseye"></i> Progresso das Metas da Comunidade</h2>
                <p style="color: var(--gray); font-size: 0.95rem; margin-bottom: 2rem;">Atingindo metas globais construídas colaborativamente por todos os usuários do EcoConecta.</p>

                <!-- Meta 1: Doação Alimentar -->
                <div class="goal-bar-container">
                    <div class="goal-header">
                        <span>Doação Solidária de Alimentos (Hortas)</span>
                        <span><?php echo $total_kg; ?>kg / 1000kg</span>
                    </div>
                    <div class="goal-bar-bg">
                        <div class="goal-bar-fill" style="width: <?php echo min(100, ($total_kg / 1000) * 100); ?>%;"></div>
                    </div>
                </div>

                <!-- Meta 2: Refeições Geradas -->
                <div class="goal-bar-container">
                    <div class="goal-header">
                        <span>Refeições Saudáveis Distribuídas</span>
                        <span><?php echo $refeicoes_geradas; ?> / 4000 pratos</span>
                    </div>
                    <div class="goal-bar-bg">
                        <div class="goal-bar-fill" style="width: <?php echo min(100, ($refeicoes_geradas / 4000) * 100); ?>%;"></div>
                    </div>
                </div>

                <!-- Meta 3: Ecopontos e Hortas Mapeados -->
                <?php 
                $pontos_mapeados = array_sum($pontos); 
                ?>
                <div class="goal-bar-container">
                    <div class="goal-header">
                        <span>Pontos de Impacto Mapeados</span>
                        <span><?php echo $pontos_mapeados; ?> / 50 pontos</span>
                    </div>
                    <div class="goal-bar-bg">
                        <div class="goal-bar-fill" style="width: <?php echo min(100, ($pontos_mapeados / 50) * 100); ?>%;"></div>
                    </div>
                </div>

                <!-- Meta 4: Evitar Emissão de CO2 -->
                <div class="goal-bar-container">
                    <div class="goal-header">
                        <span>Redução de Pegada de Carbono (CO₂)</span>
                        <span><?php echo number_format($co2_total_salvo, 1); ?>kg / 500kg</span>
                    </div>
                    <div class="goal-bar-bg">
                        <div class="goal-bar-fill" style="width: <?php echo min(100, ($co2_total_salvo / 500) * 100); ?>%;"></div>
                    </div>
                </div>
            </div>

            <!-- Painel Direito: Divisão da Rede -->
            <div class="detail-panel">
                <h2><i class="fas fa-network-wired"></i> Nossa Rede em Números</h2>
                <div class="sponsors-list">
                    <div class="sponsor-item">
                        <i class="fas fa-seedling"></i>
                        <div class="sponsor-item-info">
                            <h4><?php echo $pontos['horta']; ?> Hortas Comunitárias</h4>
                            <p>Fornecendo alimentos locais frescos</p>
                        </div>
                    </div>
                    
                    <div class="sponsor-item">
                        <i class="fas fa-recycle"></i>
                        <div class="sponsor-item-info">
                            <h4><?php echo $pontos['coleta']; ?> Ecopontos e Coletas</h4>
                            <p>Evitando descarte incorreto de resíduos</p>
                        </div>
                    </div>
                    
                    <div class="sponsor-item">
                        <i class="fas fa-store"></i>
                        <div class="sponsor-item-info">
                            <h4><?php echo $pontos['loja'] + $pontos['troca']; ?> Lojas & Trocas</h4>
                            <p>Fomentando a economia circular urbana</p>
                        </div>
                    </div>

                    <div class="sponsor-item">
                        <i class="fas fa-calendar-check"></i>
                        <div class="sponsor-item-info">
                            <h4><?php echo $total_eventos; ?> Mutirões Realizados</h4>
                            <p>Organizados de forma totalmente coletiva</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer style="text-align: center; padding: 40px; color: #666; background: #fff; margin-top: 4rem; border-top: 1px solid rgba(0,0,0,0.05);">
        &copy; 2026 EcoConecta - Desenvolvido com <i class="fas fa-heart" style="color: #e91e63;"></i> para um mundo melhor.
    </footer>
    <script src="js/menu.js"></script>
</body>
</html>
