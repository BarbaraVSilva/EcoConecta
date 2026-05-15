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

if ($usuario['tipo_perfil'] === 'empresa') {
    $stmtOp = $conn->prepare("SELECT * FROM oportunidades WHERE criador_id = ? ORDER BY data_publicacao DESC");
    $stmtOp->bind_param("i", $id);
    $stmtOp->execute();
    $resultOp = $stmtOp->get_result();
    $minhas_oportunidades = $resultOp->fetch_all(MYSQLI_ASSOC);
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
            <button class="hamburger" id="hamburger" aria-label="Menu"><span></span><span></span><span></span></button><nav class="nav-links" id="nav-links">
                <a href="index.php">Início</a>
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
                        Ganhe pontos participando de mutirões e ajudando a manter nosso mapa atualizado!
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
                            <textarea id="bio_curriculo" name="bio_curriculo" class="form-control" rows="6"><?php echo htmlspecialchars($usuario['bio_curriculo'] ?? ''); ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar Currículo</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
<script src="js/menu.js"></script>
</body>
</html>



