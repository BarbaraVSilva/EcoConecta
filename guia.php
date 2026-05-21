<?php
session_start();
require_once 'config/conexao.php';

$user_id = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : null;
$user_perfil = isset($_SESSION['tipo_perfil']) ? $_SESSION['tipo_perfil'] : '';

try {
    $result = $conn->query("SELECT * FROM guia_educacional ORDER BY data_criacao DESC");
    $artigos = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $artigos[] = $row;
        }
    }
} catch (Exception $e) {
    $artigos = [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guia Educacional - EcoConecta</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .page-header { padding-top: 8rem; padding-bottom: 2rem; text-align: center; }
        .guia-card { border-left: 5px solid var(--primary); padding: 2rem; background: var(--white); box-shadow: var(--shadow); border-radius: 0 15px 15px 0; margin-bottom: 2.5rem; }
        .quiz-box {
            background: #f4fbf7;
            border: 1px solid #c8e6c9;
            border-radius: 12px;
            padding: 20px;
            margin-top: 25px;
            box-shadow: 0 4px 10px rgba(46,125,50,0.02);
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
            <button class="hamburger" id="hamburger" aria-label="Menu"><span></span><span></span><span></span></button>
            <nav class="nav-links" aria-label="Menu principal" id="nav-links">
                <a href="index.php">Início</a>
                <a href="mapa.php">Mapa de Impacto</a>
                <div class="dropdown open">
                    <a href="javascript:void(0)" class="active">Comunidade ▾</a>
                    <div class="dropdown-content">
                        <a href="eventos.php">Mutirões</a>
                        <a href="marketplace.php">Loja & Trocas</a>
                        <a href="guia.php" style="color:var(--primary)!important; font-weight:bold;">Guia Educacional</a>
                        <a href="ecoloja.php">EcoLoja</a>
                        <a href="dashboard.php">Painel de Impacto</a>
                    </div>
                </div>
                <a href="oportunidades.php">Oportunidades</a>
                <a href="sobre.php">Sobre</a>
                <?php if($user_id): ?>
                    <a href="perfil.php" style="font-weight:bold; color:var(--primary);">Meu Perfil</a>
                    <a href="logout.php" class="btn btn-outline">Sair</a>
                <?php else: ?>
                    <a href="login.php">Entrar</a>
                    <a href="cadastro.php" class="btn btn-primary">Cadastre-se</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
<main id="conteudo-principal" tabindex="-1">

    <div class="container page-header">
        <h1>Guia <span>Educacional</span></h1>
        <p>Aprenda práticas sustentáveis e leve a ecologia para o seu dia a dia.</p>
    </div>

    <div class="container" style="padding-top: 0; max-width: 800px;">
        <?php foreach ($artigos as $a): ?>
            <div class="guia-card">
                <span class="tag" style="background: var(--light); color: var(--dark);"><?php echo htmlspecialchars($a['categoria']); ?></span>
                <h2 style="margin: 0.5rem 0;"><?php echo htmlspecialchars($a['titulo']); ?></h2>
                <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 1.5rem;">Publicado em <?php echo date('d/m/Y', strtotime($a['data_criacao'])); ?></p>
                <div style="line-height: 1.8; color: #444;">
                    <?php echo nl2br(htmlspecialchars($a['conteudo'])); ?>
                </div>

                <!-- Busca Quiz deste Guia -->
                <?php
                $quiz_stmt = $conn->prepare("SELECT * FROM guia_quizzes WHERE guia_id = ?");
                $quiz_stmt->bind_param("i", $a['id']);
                $quiz_stmt->execute();
                $quiz = $quiz_stmt->get_result()->fetch_assoc();

                $quiz_concluido = false;
                if ($quiz && $user_id) {
                    $c_stmt = $conn->prepare("SELECT id FROM usuario_quizzes WHERE usuario_id = ? AND guia_id = ?");
                    $c_stmt->bind_param("ii", $user_id, $a['id']);
                    $c_stmt->execute();
                    $quiz_concluido = $c_stmt->get_result()->num_rows > 0;
                }
                ?>

                <?php if($quiz): ?>
                    <div class="quiz-box">
                        <h3 style="color:#2e7d32; font-size:1.1rem; margin-bottom:10px;"><i class="fas fa-question-circle"></i> Teste Seus Conhecimentos (+15 EcoPontos)</h3>
                        <?php if($quiz_concluido): ?>
                            <p style="color:#2e7d32; font-weight:bold; font-size:0.9rem; margin-top:5px;"><i class="fas fa-check-circle"></i> Você respondeu corretamente a este quiz e garantiu seus pontos!</p>
                        <?php else: ?>
                            <p style="font-size:0.95rem; font-weight:600; margin-bottom:15px; color:#333;"><?php echo htmlspecialchars($quiz['pergunta']); ?></p>
                            <form onsubmit="responderQuiz(event, <?php echo $a['id']; ?>)">
                                <label style="display:block; margin-bottom:8px; font-size:0.9rem; cursor:pointer; color:#444;">
                                    <input type="radio" name="resposta" value="A" required style="margin-right:8px;"> A) <?php echo htmlspecialchars($quiz['opcao_a']); ?>
                                </label>
                                <label style="display:block; margin-bottom:8px; font-size:0.9rem; cursor:pointer; color:#444;">
                                    <input type="radio" name="resposta" value="B" style="margin-right:8px;"> B) <?php echo htmlspecialchars($quiz['opcao_b']); ?>
                                </label>
                                <label style="display:block; margin-bottom:15px; font-size:0.9rem; cursor:pointer; color:#444;">
                                    <input type="radio" name="resposta" value="C" style="margin-right:8px;"> C) <?php echo htmlspecialchars($quiz['opcao_c']); ?>
                                </label>
                                
                                <?php if($user_id && $user_perfil === 'cidadao'): ?>
                                    <button type="submit" class="btn btn-primary" style="padding:0.4rem 1.2rem; font-size:0.85rem;">Enviar Resposta</button>
                                <?php elseif($user_id): ?>
                                    <span style="font-size:0.8rem; color:var(--gray); font-style:italic;">Disponível apenas para perfil Cidadão.</span>
                                <?php else: ?>
                                    <a href="login.php" class="btn btn-outline" style="padding:0.4rem 1.2rem; font-size:0.85rem; text-decoration:none;">Faça Login para Responder</a>
                                <?php endif; ?>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        <?php if(empty($artigos)) echo "<p style='text-align:center;'>Nenhum artigo disponível.</p>"; ?>
    </div>

    <!-- Handler de Quiz via AJAX -->
    <script>
        function responderQuiz(event, guiaId) {
            event.preventDefault();
            const form = event.target;
            const resposta = form.querySelector('input[name="resposta"]:checked').value;
            
            fetch('controllers/processa_quiz.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `guia_id=${guiaId}&resposta=${resposta}`
            })
            .then(response => response.json())
            .then(res => {
                if (res.success) {
                    alert(res.message);
                    location.reload();
                } else {
                    alert(res.message);
                }
            })
            .catch(err => {
                alert('Erro ao processar quiz. Tente novamente.');
            });
        }
    </script>
    <script src="js/menu.js"></script>
<script src="js/accessibility.js"></script>
</body>
</html>
