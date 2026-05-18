<?php
session_start();
require_once 'config/conexao.php';

try {
    $result = $conn->query("SELECT m.*, u.nome as criador_nome FROM marketplace m JOIN usuarios u ON m.criador_id = u.id ORDER BY m.data_criacao DESC");
    $produtos = $result->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    $produtos = [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace Sustentável - EcoConecta</title>
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
                    <a href="javascript:void(0)" class="active">Comunidade ▾</a>
                    <div class="dropdown-content">
                        <a href="eventos.php">Mutirões</a>
                        <a href="marketplace.php" style="color:var(--primary)!important; font-weight:bold;">Loja & Trocas</a>
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

    <div class="container page-header">
        <h1>Loja & <span>Trocas Sustentáveis</span></h1>
        <p>Apoie artesãos de upcycling ou troque sementes com a comunidade. <br> <b>Cada compra aqui retira resíduos do meio ambiente e gera renda local.</b></p>
        
        <?php if(isset($_SESSION['usuario_id'])): ?>
            <button onclick="document.getElementById('modal-anuncio').style.display='block'" class="btn btn-primary" style="margin-top: 1rem;">+ Novo Anúncio</button>
        <?php else: ?>
            <p style="margin-top: 1rem;"><a href="login.php" style="color: var(--primary);">Faça login</a> para anunciar.</p>
        <?php endif; ?>
    </div>

    <div class="container" style="padding-top: 0;">
        <div class="oportunidades-grid">
            <?php foreach ($produtos as $p): ?>
                <div class="glass-card">
                    <?php if($p['imagem']): ?>
                        <img src="uploads/marketplace/<?php echo htmlspecialchars($p['imagem']); ?>" class="marketplace-img" alt="Produto">
                    <?php else: ?>
                        <div class="marketplace-img" style="background:#eee; display:flex; align-items:center; justify-content:center; color:#999;">Sem Imagem</div>
                    <?php endif; ?>
                    
                    <span class="tag <?php echo $p['tipo_anuncio'] === 'upcycling' ? 'vaga' : 'curso'; ?>">
                        <?php echo str_replace('_', ' ', strtoupper($p['tipo_anuncio'])); ?>
                    </span>
                    <h3 style="margin: 0.5rem 0;"><?php echo htmlspecialchars($p['titulo']); ?></h3>
                    <p style="color: var(--primary-dark); font-weight: bold; margin-bottom: 0.5rem;">
                        <?php echo $p['preco'] > 0 ? 'R$ ' . number_format($p['preco'], 2, ',', '.') : 'Gratuito / Troca'; ?>
                    </p>
                    <p style="font-size: 0.9rem; margin-bottom: 1rem;"><?php echo htmlspecialchars($p['descricao']); ?></p>
                    <p style="font-size: 0.8rem; color: var(--gray);">Anunciante: <?php echo htmlspecialchars($p['criador_nome']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Modal Novo AnÃºncio -->
    <div id="modal-anuncio" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="document.getElementById('modal-anuncio').style.display='none'">&times;</span>
            <h2>Criar Anúncio</h2>
            <form action="controllers/processa_marketplace.php" method="POST" enctype="multipart/form-data" style="margin-top: 1.5rem;">
                <div class="form-group">
                    <label>Título</label>
                    <input type="text" name="titulo" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Tipo</label>
                    <select name="tipo" class="form-control">
                        <option value="upcycling">Upcycling (Produto Reciclado)</option>
                        <option value="troca_semente">Troca de Sementes/Mudas</option>
                        <option value="doacao">Doação</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Preço (R$ - opcional)</label>
                    <input type="number" step="0.01" name="preco" class="form-control" value="0.00">
                </div>
                <div class="form-group">
                    <label>Descrição</label>
                    <textarea name="descricao" class="form-control" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label>Imagem (Opcional)</label>
                    <input type="file" name="imagem" class="form-control" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Publicar</button>
            </form>
        </div>
    </div>
<script src="js/menu.js"></script>
</body>
</html>



