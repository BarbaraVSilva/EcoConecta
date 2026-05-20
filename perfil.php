<?php
session_start();
require_once 'config/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['usuario_id'];
$stmt = $conn->prepare("SELECT nome, eco_pontos, bio_curriculo, tipo_perfil FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

// Consultas específicas por tipo de perfil
if ($usuario['tipo_perfil'] === 'empresa') {
    // Busca oportunidades criadas
    $stmtOp = $conn->prepare("SELECT * FROM oportunidades WHERE criador_id = ? ORDER BY data_publicacao DESC");
    $stmtOp->bind_param("i", $id);
    $stmtOp->execute();
    $resultOp = $stmtOp->get_result();
    $minhas_oportunidades = [];
    if ($resultOp) {
        while ($row = $resultOp->fetch_assoc()) {
            $minhas_oportunidades[] = $row;
        }
    }

    // Busca cupons de clientes resgatados
    $stmtCli = $conn->prepare("
        SELECT r.codigo_cupom, r.status, r.data_resgate, rec.titulo, u.nome AS cliente_nome 
        FROM resgates r 
        JOIN recompensas rec ON r.recompensa_id = rec.id 
        JOIN usuarios u ON r.usuario_id = u.id 
        WHERE rec.empresa_id = ? 
        ORDER BY r.data_resgate DESC
    ");
    $stmtCli->bind_param("i", $id);
    $stmtCli->execute();
    $resultCli = $stmtCli->get_result();
    $cupons_clientes = [];
    if ($resultCli) {
        while ($row = $resultCli->fetch_assoc()) {
            $cupons_clientes[] = $row;
        }
    }
} else {
    // Busca cupons resgatados pelo cidadão
    $stmtCup = $conn->prepare("
        SELECT r.codigo_cupom, r.status, r.data_resgate, rec.titulo, rec.descricao, u.nome AS empresa_nome 
        FROM resgates r 
        JOIN recompensas rec ON r.recompensa_id = rec.id 
        JOIN usuarios u ON rec.empresa_id = u.id 
        WHERE r.usuario_id = ? 
        ORDER BY r.data_resgate DESC
    ");
    $stmtCup->bind_param("i", $id);
    $stmtCup->execute();
    $resultCup = $stmtCup->get_result();
    $meus_cupons = [];
    if ($resultCup) {
        while ($row = $resultCup->fetch_assoc()) {
            $meus_cupons[] = $row;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bio = $_POST['bio_curriculo'];
    $stmt = $conn->prepare("UPDATE usuarios SET bio_curriculo = ? WHERE id = ?");
    $stmt->bind_param("si", $bio, $id);
    $stmt->execute();
    $usuario['bio_curriculo'] = $bio;
    $sucesso = "Currículo atualizado!";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - EcoConecta</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .page-header { padding-top: 8rem; padding-bottom: 2rem; text-align: center; }
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
                <a href="perfil.php" class="active" style="font-weight:bold; color:var(--primary);">Meu Perfil</a>
                <a href="logout.php" class="btn btn-outline">Sair</a>
            </nav>
        </div>
    </header>

    <div class="container page-header">
        <h1>Meu <span>Perfil</span></h1>
    </div>

    <div class="container" style="padding-top: 0;">
        <div class="profile-grid">
            <!-- Sidebar -->
            <div class="glass-card profile-sidebar">
                <?php if ($usuario['tipo_perfil'] === 'empresa'): ?>
                    <div style="font-size: 4rem; margin-bottom: 1rem;">🏢</div>
                    <h2><?php echo htmlspecialchars($usuario['nome']); ?></h2>
                    <p style="margin-top: 1rem; color: var(--gray); font-size: 0.9rem; font-weight: bold;">Perfil Corporativo</p>
                <?php else: ?>
                    <div style="font-size: 4rem; margin-bottom: 1rem;">👤</div>
                    <h2><?php echo htmlspecialchars($usuario['nome']); ?></h2>
                    <div class="eco-pontos-badge" style="margin-top: 1rem;">
                        🍃 <?php echo $usuario['eco_pontos']; ?> EcoPontos
                    </div>
                    <p style="margin-top: 1rem; color: var(--gray); font-size: 0.9rem;">
                        Ganhe pontos participando de mutirões, respondendo a quizzes e ajudando a manter nosso mapa atualizado!
                    </p>
                <?php endif; ?>
            </div>

            <!-- Content Area -->
            <div class="glass-card">
                <?php if ($usuario['tipo_perfil'] === 'empresa'): ?>
                    <h3>📋 Minhas Vagas Publicadas</h3>
                    <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 1.5rem;">
                        Acompanhe as oportunidades de emprego e cursos que você ofereceu para a comunidade.
                    </p>
                    <?php if(isset($minhas_oportunidades) && count($minhas_oportunidades) > 0): ?>
                        <ul style="list-style: none; padding: 0;">
                            <?php foreach($minhas_oportunidades as $op): ?>
                                <li style="padding: 1rem; border: 1px solid var(--glass-border); border-radius: 10px; margin-bottom: 1rem; background: var(--light);">
                                    <strong style="color: var(--primary-dark); font-size: 1.1rem;"><?php echo htmlspecialchars($op['titulo']); ?></strong> 
                                    <span class="tag <?php echo $op['tipo']; ?>" style="margin-bottom: 0; margin-left: 0.5rem; font-size: 0.7rem;"><?php echo $op['tipo']; ?></span>
                                    <div style="font-size: 0.8rem; color: var(--gray); margin-top: 0.5rem;">Publicado em: <?php echo date('d/m/Y', strtotime($op['data_publicacao'])); ?></div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p style="background: rgba(46, 204, 113, 0.1); padding: 1rem; border-radius: 10px; color: var(--primary-dark);">Você ainda não publicou nenhuma oportunidade.</p>
                    <?php endif; ?>
                    <a href="oportunidades.php" class="btn btn-primary" style="margin-top: 1rem; display: inline-block;">Ir para o Mural e Publicar</a>

                    <!-- Controle de Cupons Resgatados por Clientes -->
                    <h3 style="margin-top: 3rem; border-top: 1px solid #eee; padding-top: 2rem;"><i class="fas fa-barcode"></i> Controle de Cupons Resgatados</h3>
                    <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 1.5rem;">Gerencie os cupons da EcoLoja que clientes geraram para utilizar em sua empresa.</p>
                    <?php if(count($cupons_clientes) > 0): ?>
                        <div style="display:grid; grid-template-columns:1fr; gap:15px;">
                            <?php foreach($cupons_clientes as $c_cli): ?>
                                <div style="padding:15px; background:#f9f9f9; border:1px solid #eee; border-radius:10px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
                                    <div>
                                        <strong style="color:var(--dark); font-size:0.95rem;"><?php echo htmlspecialchars($c_cli['cliente_nome']); ?></strong>
                                        <div style="font-size:0.8rem; color:var(--gray); margin-top:3px;">Campanha: <?php echo htmlspecialchars($c_cli['titulo']); ?></div>
                                    </div>
                                    <div style="text-align:right;">
                                        <div style="font-family:monospace; background:#fff; border:1px solid #ccc; padding:4px 8px; border-radius:4px; font-weight:bold; color:var(--dark); font-size:0.9rem;"><?php echo $c_cli['codigo_cupom']; ?></div>
                                        <div style="font-size:0.75rem; color:#888; margin-top:4px;">Gerado em <?php echo date('d/m/Y', strtotime($c_cli['data_resgate'])); ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p style="background: rgba(0,0,0,0.02); padding: 1rem; border-radius: 10px; color: var(--gray);">Nenhum cliente resgatou ofertas da sua empresa na EcoLoja ainda.</p>
                    <?php endif; ?>

                <?php else: ?>
                    <h3>🌱 Currículo Verde</h3>
                    <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 1.5rem;">
                        Apresente suas habilidades para empresas que buscam talentos sustentáveis.
                    </p>
                    
                    <?php if(isset($sucesso)): ?>
                        <div style="color: var(--primary); margin-bottom: 1rem; font-weight: bold;"><?php echo $sucesso; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="form-group">
                            <label for="bio_curriculo">Sobre mim, cursos concluídos e engajamento:</label>
                            <textarea id="bio_curriculo" name="bio_curriculo" class="form-control" rows="6" placeholder="Ex: Estudante de engenharia apaixonado por hortas e agroecologia..."><?php echo htmlspecialchars($usuario['bio_curriculo'] ?? ''); ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar Currículo</button>
                        <a href="exporta_curriculo.php" class="btn btn-outline" style="margin-left: 10px; display: inline-flex; align-items: center; gap: 8px;"><i class="fas fa-file-pdf"></i> Imprimir / PDF</a>
                    </form>

                    <!-- Meus Cupons da EcoLoja -->
                    <h3 style="margin-top: 3rem; border-top: 1px solid #eee; padding-top: 2rem;"><i class="fas fa-ticket-alt"></i> Meus Cupons da EcoLoja</h3>
                    <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 1.5rem;">Use estes códigos de cupom nas lojas parceiras para usufruir de seus descontos e prêmios.</p>
                    <?php if(count($meus_cupons) > 0): ?>
                        <div style="display:grid; grid-template-columns:1fr; gap:15px;">
                            <?php foreach($meus_cupons as $cup): ?>
                                <div style="padding:20px; background:#f4fbf7; border:1px solid #c8e6c9; border-radius:12px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px;">
                                    <div>
                                        <strong style="color:#1b5e20; font-size:1.1rem;"><?php echo htmlspecialchars($cup['titulo']); ?></strong>
                                        <div style="font-size:0.85rem; color:var(--gray); margin-top:4px;"><?php echo htmlspecialchars($cup['descricao']); ?></div>
                                        <div style="font-size:0.8rem; color:#777; margin-top:4px;"><i class="fas fa-store"></i> Parceiro: <?php echo htmlspecialchars($cup['empresa_nome']); ?></div>
                                    </div>
                                    <div style="text-align:right;">
                                        <div style="font-family:monospace; background:#e8f5e9; border:1px dashed #2e7d32; padding:6px 12px; border-radius:6px; font-weight:bold; color:#1b5e20; font-size:1.05rem; letter-spacing:0.5px;"><?php echo $cup['codigo_cupom']; ?></div>
                                        <div style="font-size:0.75rem; color:#888; margin-top:6px;">Resgatado em <?php echo date('d/m/Y', strtotime($cup['data_resgate'])); ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p style="background: rgba(0,0,0,0.02); padding: 1rem; border-radius: 10px; color: var(--gray);">Você ainda não resgatou nenhum cupom na EcoLoja. Visite a <a href="ecoloja.php" style="color:var(--primary); font-weight:bold;">EcoLoja</a>!</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="js/menu.js"></script>
</body>
</html>
