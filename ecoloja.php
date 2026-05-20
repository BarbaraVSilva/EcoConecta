<?php
session_start();
require_once 'config/conexao.php';

$is_logged = isset($_SESSION['usuario_id']);
$user_id = $is_logged ? $_SESSION['usuario_id'] : null;
$user_perfil = $is_logged ? $_SESSION['tipo_perfil'] : '';
$user_pontos = 0;
$user_nome = "";

if ($is_logged) {
    $stmt = $conn->prepare("SELECT nome, eco_pontos FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $u_data = $stmt->get_result()->fetch_assoc();
    if ($u_data) {
        $user_pontos = intval($u_data['eco_pontos']);
        $user_nome = htmlspecialchars($u_data['nome']);
    }
}

// Busca recompensas disponíveis
$res_rec = $conn->query("
    SELECT r.*, u.nome AS empresa_nome 
    FROM recompensas r 
    JOIN usuarios u ON r.empresa_id = u.id 
    WHERE r.quantidade_disponivel > 0 
    ORDER BY r.custo_pontos ASC
");
$recompensas = [];
if ($res_rec) {
    while ($row = $res_rec->fetch_assoc()) {
        $recompensas[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoLoja - EcoConecta</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .store-container {
            padding-top: 6rem;
            padding-bottom: 4rem;
        }
        .store-header {
            text-align: center;
            margin-bottom: 3rem;
            background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(46,125,50,0.05);
        }
        .store-header h1 {
            color: #1b5e20;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        .store-header p {
            color: #2e7d32;
            font-size: 1.1rem;
        }
        .points-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #2e7d32;
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 700;
            margin-top: 15px;
            font-size: 1.1rem;
            box-shadow: 0 4px 15px rgba(46,125,50,0.2);
        }
        /* Grid de Recompensas */
        .rewards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 2rem;
        }
        .reward-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
            border: 1px solid rgba(0,0,0,0.03);
            display: flex;
            flex-direction: column;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .reward-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
        }
        .reward-banner {
            background: linear-gradient(135deg, #a5d6a7, #81c784);
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
        }
        .reward-content {
            padding: 25px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .reward-company {
            font-size: 0.8rem;
            color: var(--primary);
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        .reward-title {
            font-size: 1.3rem;
            color: var(--dark);
            font-weight: 600;
            margin-bottom: 10px;
        }
        .reward-desc {
            font-size: 0.9rem;
            color: var(--gray);
            line-height: 1.5;
            margin-bottom: 20px;
            flex-grow: 1;
        }
        .reward-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-top: 1px solid #f0f0f0;
            padding-top: 15px;
            margin-top: auto;
        }
        .reward-cost {
            font-size: 1.1rem;
            color: #1b5e20;
            font-weight: 700;
        }
        .btn-redeem {
            background: var(--primary);
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }
        .btn-redeem:hover {
            background: #1b5e20;
        }
        .btn-disabled {
            background: #e0e0e0;
            color: #9e9e9e;
            cursor: not-allowed;
        }
        /* Painel da Empresa */
        .company-panel {
            background: #f1f8e9;
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 3.5rem;
            border: 1px dashed var(--primary);
        }
        .company-panel h2 {
            color: #1b5e20;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
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
                        <a href="ecoloja.php" class="active">EcoLoja</a>
                        <a href="dashboard.php">Painel de Impacto</a>
                    </div>
                </div>
                <a href="oportunidades.php">Oportunidades</a>
                <a href="sobre.php">Sobre</a>
                <?php if($is_logged): ?>
                    <a href="perfil.php" style="font-weight:bold; color:var(--primary);">Meu Perfil</a>
                    <a href="logout.php" class="btn btn-outline">Sair</a>
                <?php else: ?>
                    <a href="login.php">Entrar</a>
                    <a href="cadastro.php" class="btn btn-primary">Cadastre-se</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <div class="container store-container">
        <div class="store-header">
            <h1>Eco<span>Loja</span> Comunitária</h1>
            <p>Troque seus EcoPontos acumulados por cupons de desconto, kits ecológicos e produtos de parceiros locais.</p>
            
            <?php if($is_logged && $user_perfil === 'cidadao'): ?>
                <div class="points-badge">
                    <i class="fas fa-leaf"></i>
                    <span>Olá, <?php echo $user_nome; ?>! Você tem <?php echo $user_pontos; ?> EcoPontos</span>
                </div>
            <?php elseif($is_logged && $user_perfil === 'empresa'): ?>
                <div class="points-badge" style="background:#0288d1;">
                    <i class="fas fa-building"></i>
                    <span>Painel Corporativo de Parcerias</span>
                </div>
            <?php else: ?>
                <div class="points-badge" style="background:#757575;">
                    <i class="fas fa-lock"></i>
                    <span>Faça login para resgatar prêmios</span>
                </div>
            <?php endif; ?>
        </div>

        <!-- Painel Exclusivo da Empresa -->
        <?php if($is_logged && $user_perfil === 'empresa'): ?>
            <div class="company-panel">
                <h2><i class="fas fa-plus-circle"></i> Cadastrar Nova Recompensa na EcoLoja</h2>
                <form action="controllers/processa_cadastro_recompensa.php" method="POST">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:15px;">
                        <div class="form-group">
                            <label>Título da Recompensa</label>
                            <input type="text" name="titulo" class="form-control" placeholder="Ex: R$ 15 de desconto em orgânicos" required>
                        </div>
                        <div class="form-group">
                            <label>Custo em EcoPontos</label>
                            <input type="number" name="custo_pontos" class="form-control" placeholder="Ex: 120" required>
                        </div>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:15px;">
                        <div class="form-group">
                            <label>Quantidade Disponível</label>
                            <input type="number" name="quantidade" class="form-control" placeholder="Ex: 10" required>
                        </div>
                        <div class="form-group">
                            <label>Descrição Curta</label>
                            <input type="text" name="descricao" class="form-control" placeholder="Ex: Válido em compras acima de R$ 50" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Publicar Oferta na EcoLoja</button>
                </form>
            </div>
        <?php endif; ?>

        <!-- Galeria de Recompensas -->
        <h2 style="color: var(--dark); font-size: 1.6rem; margin-bottom: 1.5rem;"><i class="fas fa-tags"></i> Recompensas Disponíveis</h2>
        
        <div class="rewards-grid">
            <?php if(count($recompensas) === 0): ?>
                <p style="color:var(--gray); grid-column:1/-1; text-align:center; padding:40px;">Nenhuma recompensa cadastrada no momento. Volte mais tarde!</p>
            <?php else: ?>
                <?php foreach($recompensas as $rec): ?>
                    <?php 
                    $cost = intval($rec['custo_pontos']);
                    $has_points = ($user_pontos >= $cost);
                    $can_redeem = ($is_logged && $user_perfil === 'cidadao' && $has_points);
                    ?>
                    <div class="reward-card">
                        <div class="reward-banner">
                            <?php if(strpos(strtolower($rec['titulo']), 'desconto') !== false): ?>
                                🏷️
                            <?php elseif(strpos(strtolower($rec['titulo']), 'kit') !== false): ?>
                                📦
                            <?php else: ?>
                                🌿
                            <?php endif; ?>
                        </div>
                        <div class="reward-content">
                            <span class="reward-company"><i class="fas fa-store"></i> <?php echo htmlspecialchars($rec['empresa_nome']); ?></span>
                            <h3 class="reward-title"><?php echo htmlspecialchars($rec['titulo']); ?></h3>
                            <p class="reward-desc"><?php echo htmlspecialchars($rec['descricao']); ?></p>
                            
                            <div class="reward-footer">
                                <span class="reward-cost"><?php echo $cost; ?> EcoPontos</span>
                                
                                <?php if(!$is_logged): ?>
                                    <button class="btn-redeem btn-disabled" onclick="alert('Faça login para poder resgatar recompensas!')">Entrar</button>
                                <?php elseif($user_perfil !== 'cidadao'): ?>
                                    <span style="font-size:0.8rem; color:var(--gray); font-style:italic;">Exclusivo Cidadão</span>
                                <?php elseif($can_redeem): ?>
                                    <button class="btn-redeem" onclick="resgatarRecompensa(<?php echo $rec['id']; ?>, '<?php echo addslashes($rec['titulo']); ?>')">Resgatar</button>
                                <?php else: ?>
                                    <button class="btn-redeem btn-disabled" disabled>Saldo Insuficiente</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <footer style="text-align: center; padding: 40px; color: #666; background: #fff; margin-top: 4rem; border-top: 1px solid rgba(0,0,0,0.05);">
        &copy; 2026 EcoConecta - Desenvolvido com <i class="fas fa-heart" style="color: #e91e63;"></i> para um mundo melhor.
    </footer>

    <!-- Função de Resgate via AJAX -->
    <script>
        function resgatarRecompensa(id, titulo) {
            if (confirm(`Deseja resgatar a recompensa "${titulo}"? O valor em EcoPontos será deduzido do seu saldo.`)) {
                fetch('controllers/processa_resgate.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'recompensa_id=' + id
                })
                .then(response => response.json())
                .then(res => {
                    if (res.success) {
                        alert(`Parabéns! Recompensa resgatada com sucesso.\nSeu código de cupom é: ${res.coupon}\nVocê pode acessá-lo a qualquer momento em seu perfil.`);
                        location.reload();
                    } else {
                        alert(res.message || 'Erro ao resgatar recompensa.');
                    }
                })
                .catch(err => {
                    alert('Erro de conexão. Tente novamente mais tarde.');
                });
            }
        }
    </script>
    <script src="js/menu.js"></script>
</body>
</html>
