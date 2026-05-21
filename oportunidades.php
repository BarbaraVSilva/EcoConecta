<?php
session_start();
require_once 'config/conexao.php';

// Bloqueia acesso sem login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

try {
    // Busca informações do usuário logado
    $stmtUser = $conn->prepare("SELECT tipo_perfil FROM usuarios WHERE id = ?");
    $stmtUser->bind_param("i", $_SESSION['usuario_id']);
    $stmtUser->execute();
    $resultUser = $stmtUser->get_result();
    $userSession = $resultUser->fetch_assoc();
    $tipo_perfil = $userSession['tipo_perfil'] ?? 'cidadao';

    $result = $conn->query("SELECT * FROM oportunidades ORDER BY data_publicacao DESC");
    $oportunidades = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $oportunidades[] = $row;
        }
    }
} catch (Exception $e) {
    $oportunidades = [];
    $tipo_perfil = 'cidadao';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oportunidades - EcoVocação</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .page-header {
            padding-top: 8rem;
            padding-bottom: 2rem;
            text-align: center;
        }
        .oportunidade-card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .perfil-section {
            background: var(--white);
            padding: 1.5rem;
            border-radius: 15px;
            margin-bottom: 3rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow);
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
            <button class="hamburger" id="hamburger" aria-label="Menu"><span></span><span></span><span></span></button><nav class="nav-links" aria-label="Menu principal" id="nav-links">
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
                <a href="oportunidades.php" class="active">Oportunidades</a>
                <a href="sobre.php">Sobre</a>
                <a href="perfil.php" style="font-weight:bold; color:var(--primary);">Meu Perfil</a>
                <a href="logout.php" class="btn btn-outline">Sair</a>
            </nav>
        </div>
    </header>
<main id="conteudo-principal" tabindex="-1">

    <div class="container page-header">
        <h1>Mural de <span>Oportunidades</span></h1>
        <p>Encontre vagas de emprego na economia verde e cursos de capacitação.</p>
        
        <?php if ($tipo_perfil === 'empresa'): ?>
            <button onclick="document.getElementById('modal-oportunidade').style.display='block'" class="btn btn-primary" style="margin-top: 1rem;">+ Publicar Vaga/Curso</button>
        <?php endif; ?>
    </div>

    <div class="container" style="padding-top: 0;">
        <div class="perfil-section">
            <div>
                <h3>Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</h3>
                <p style="color: var(--gray); font-size: 0.9rem;">Gerencie seu perfil e suas oportunidades.</p>
            </div>
            <div>
                <!-- Opção para exclusão de conta via LGPD -->
                <form action="controllers/lgpd_exclusao.php" method="POST" onsubmit="return confirm('ATENÇÃO: Tem certeza que deseja apagar sua conta? Todos os seus dados serão excluídos definitivamente (LGPD).');">
                    <button type="submit" class="btn" style="background-color: #E74C3C; color: white; border: none;">Excluir Minha Conta (LGPD)</button>
                </form>
            </div>
        </div>

        <div class="oportunidades-grid">
            <?php if (count($oportunidades) > 0): ?>
                <?php foreach ($oportunidades as $op): ?>
                    <div class="glass-card oportunidade-card">
                        <div>
                            <span class="tag <?php echo $op['tipo']; ?>">
                                <?php echo $op['tipo'] === 'vaga' ? 'Vaga de Emprego' : 'Curso/Oficina'; ?>
                            </span>
                            <h3 style="margin-bottom: 0.5rem;"><?php echo htmlspecialchars($op['titulo']); ?></h3>
                            <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 1rem;">
                                Publicado em: <?php echo date('d/m/Y', strtotime($op['data_publicacao'])); ?>
                            </p>
                            <p style="margin-bottom: 1.5rem;"><?php echo htmlspecialchars($op['descricao']); ?></p>
                        </div>
                        <a href="<?php echo htmlspecialchars($op['link_externo']); ?>" class="btn btn-outline" style="text-align: center; width: 100%;">Saiba Mais / Inscrever-se</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhuma oportunidade disponível no momento.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal Nova Oportunidade -->
    <div id="modal-oportunidade" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="document.getElementById('modal-oportunidade').style.display='none'">&times;</span>
            <h2>Publicar Oportunidade</h2>
            <form action="controllers/processa_oportunidade.php" method="POST" style="margin-top: 1.5rem;">
                <div class="form-group">
                    <label>Título da Oportunidade</label>
                    <input type="text" name="titulo" class="form-control" placeholder="Ex: Analista de Sustentabilidade" required>
                </div>
                <div class="form-group">
                    <label>Tipo</label>
                    <select name="tipo" class="form-control">
                        <option value="vaga">Vaga de Emprego</option>
                        <option value="curso">Curso/Capacitação</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Link Externo (Inscrição)</label>
                    <input type="url" name="link_externo" class="form-control" placeholder="https://..." required>
                </div>
                <div class="form-group">
                    <label>Descrição</label>
                    <textarea name="descricao" class="form-control" rows="4" placeholder="Detalhes sobre a vaga ou curso..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Publicar Agora</button>
            </form>
        </div>
    </div>

<script src="js/menu.js"></script>
<script src="js/accessibility.js"></script>
</body>
</html>

