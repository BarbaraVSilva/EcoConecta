<?php
session_start();
require_once 'config/conexao.php';

try {
    $result = $conn->query("SELECT e.*, u.nome as criador_nome FROM eventos e JOIN usuarios u ON e.criador_id = u.id ORDER BY e.data_evento ASC");
    $eventos = $result->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    $eventos = [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos e Mutirões - EcoConecta</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .page-header { padding-top: 8rem; padding-bottom: 2rem; text-align: center; }
        .evento-data { background: var(--primary); color: white; padding: 1rem; border-radius: 10px; text-align: center; font-weight: bold; min-width: 100px; }
        .evento-data span { display: block; font-size: 2rem; line-height: 1; }
        .evento-card { display: flex; gap: 1.5rem; align-items: center; }
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
                    <a href="javascript:void(0)" class="active">Comunidade ▾</a>
                    <div class="dropdown-content">
                        <a href="eventos.php" style="color:var(--primary)!important; font-weight:bold;">Mutirões</a>
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

    <div class="container page-header">
        <h1>Calendário de <span>Mutirões</span></h1>
        <p>Participe de eventos locais e faça a diferença na prática.</p>
        <?php if(isset($_SESSION['usuario_id'])): ?>
            <button onclick="document.getElementById('modal-evento').style.display='block'" class="btn btn-primary" style="margin-top: 1rem;">+ Organizar Evento</button>
        <?php endif; ?>
    </div>

    <div class="container" style="padding-top: 0; max-width: 900px;">
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <?php foreach ($eventos as $e): 
                $data = new DateTime($e['data_evento']);
                $meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
            ?>
                <div class="glass-card evento-card">
                    <div class="evento-data">
                        <span><?php echo $data->format('d'); ?></span>
                        <?php echo $meses[$data->format('n') - 1]; ?>
                    </div>
                    <div>
                        <h3 style="margin-bottom: 0.5rem;"><?php echo htmlspecialchars($e['titulo']); ?></h3>
                        <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 0.5rem;">
                            ðŸ“ <?php echo htmlspecialchars($e['localizacao']); ?> | ðŸ•’ <?php echo $data->format('H:i'); ?>
                        </p>
                        <p><?php echo htmlspecialchars($e['descricao']); ?></p>
                        <p style="font-size: 0.8rem; color: var(--primary); margin-top: 0.5rem;">Organizado por: <?php echo htmlspecialchars($e['criador_nome']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if(empty($eventos)) echo "<p style='text-align:center;'>Nenhum evento programado.</p>"; ?>
        </div>
    </div>

    <!-- Modal Novo Evento -->
    <div id="modal-evento" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="document.getElementById('modal-evento').style.display='none'">&times;</span>
            <h2>Criar Mutirão/Evento</h2>
            <form action="controllers/processa_evento.php" method="POST" style="margin-top: 1.5rem;">
                <div class="form-group">
                    <label>Título do Evento</label>
                    <input type="text" name="titulo" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Data e Hora</label>
                    <input type="datetime-local" name="data_evento" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Localização</label>
                    <input type="text" name="localizacao" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Descrição</label>
                    <textarea name="descricao" class="form-control" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Agendar Evento</button>
            </form>
        </div>
    </div>
<script src="js/menu.js"></script>
</body>
</html>



