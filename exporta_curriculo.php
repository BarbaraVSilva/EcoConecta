<?php
session_start();
require_once 'config/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['usuario_id'];

// Obtém dados do usuário
$stmt = $conn->prepare("SELECT nome, email, eco_pontos, bio_curriculo, tipo_perfil, data_criacao FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user || $user['tipo_perfil'] !== 'cidadao') {
    echo "Apenas perfis de cidadão possuem Currículo Verde.";
    exit;
}

// 1. Quizzes/Cursos Concluídos (guia_educacional)
$q_stmt = $conn->prepare("
    SELECT g.titulo, g.categoria, uq.data_conclusao 
    FROM usuario_quizzes uq 
    JOIN guia_educacional g ON uq.guia_id = g.id 
    WHERE uq.usuario_id = ? 
    ORDER BY uq.data_conclusao DESC
");
$q_stmt->bind_param("i", $user_id);
$q_stmt->execute();
$cursos = $q_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// 2. Pontos Mapeados pelo Usuário (pontos_impacto)
$p_stmt = $conn->prepare("SELECT COUNT(id) AS total FROM pontos_impacto WHERE criador_id = ?");
$p_stmt->bind_param("i", $user_id);
$p_stmt->execute();
$pontos_mapeados = intval($p_stmt->get_result()->fetch_assoc()['total']);

// 3. Reports de Problemas Enviados (mapa_reports)
$r_stmt = $conn->prepare("SELECT COUNT(id) AS total FROM mapa_reports WHERE usuario_id = ?");
$r_stmt->bind_param("i", $user_id);
$r_stmt->execute();
$reports_enviados = intval($r_stmt->get_result()->fetch_assoc()['total']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Currículo Verde - <?php echo htmlspecialchars($user['nome']); ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #f4f6f4;
            font-family: 'Outfit', sans-serif;
            color: #333;
            padding: 40px 20px;
        }
        .cv-container {
            background: white;
            max-width: 800px;
            margin: 0 auto;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.06);
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.03);
        }
        .cv-header {
            background: linear-gradient(135deg, #2e7d32, #1b5e20);
            color: white;
            padding: 40px;
            position: relative;
        }
        .cv-header h1 {
            font-size: 2.2rem;
            margin-bottom: 5px;
            color: white;
        }
        .cv-header p.email {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 20px;
        }
        .cv-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.15);
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: bold;
            border: 1px solid rgba(255,255,255,0.25);
        }
        .cv-body {
            padding: 40px;
        }
        .cv-section {
            margin-bottom: 35px;
        }
        .cv-section h2 {
            font-size: 1.3rem;
            color: #1b5e20;
            border-bottom: 2px solid #e8f5e9;
            padding-bottom: 8px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .cv-section p.bio {
            font-size: 1rem;
            line-height: 1.6;
            color: #555;
            font-style: italic;
        }
        /* Grid de Conquistas */
        .cv-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .cv-stat-card {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 12px;
            border: 1px solid rgba(0,0,0,0.02);
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .cv-stat-card i {
            font-size: 1.8rem;
            color: #2e7d32;
        }
        .cv-stat-card h3 {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 3px;
            text-transform: uppercase;
        }
        .cv-stat-card h4 {
            font-size: 1.4rem;
            color: #333;
            font-weight: 700;
        }
        /* Histórico de Cursos */
        .course-item {
            padding: 15px;
            background: #f4fbf7;
            border-radius: 10px;
            margin-bottom: 12px;
            border-left: 4px solid #2e7d32;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .course-info h4 {
            font-size: 1rem;
            color: #333;
            margin-bottom: 3px;
        }
        .course-info span.category {
            font-size: 0.75rem;
            background: #c8e6c9;
            color: #1b5e20;
            padding: 2px 8px;
            border-radius: 50px;
            font-weight: bold;
        }
        .course-date {
            font-size: 0.8rem;
            color: #777;
        }
        /* Botões de Ação */
        .action-bar {
            max-width: 800px;
            margin: 0 auto 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-print {
            background: #2e7d32;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(46,125,50,0.2);
            transition: 0.2s;
        }
        .btn-print:hover {
            background: #1b5e20;
        }

        /* Estilos de Impressão */
        @media print {
            body {
                background: white;
                color: black;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .cv-container {
                box-shadow: none !important;
                border: none !important;
                max-width: 100% !important;
                margin: 0 !important;
                border-radius: 0 !important;
            }
            .cv-header {
                background: #1b5e20 !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .course-item {
                background: #f9f9f9 !important;
                border-left: 4px solid #1b5e20 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
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
    <div class="action-bar no-print">
        <a href="perfil.php" style="color: #2e7d32; text-decoration: none; font-weight: bold;"><i class="fas fa-arrow-left"></i> Voltar ao Perfil</a>
        <button onclick="window.print()" class="btn-print"><i class="fas fa-print"></i> Imprimir / Salvar em PDF</button>
    </div>

    <div class="cv-container">
        <!-- Cabeçalho -->
        <div class="cv-header">
            <h1><?php echo htmlspecialchars($user['nome']); ?></h1>
            <p class="email"><i class="far fa-envelope"></i> <?php echo htmlspecialchars($user['email']); ?></p>
            <div class="cv-badge">
                <i class="fas fa-leaf"></i>
                <span>Nível Verde: Agente de Impacto com <?php echo $user['eco_pontos']; ?> EcoPontos</span>
            </div>
        </div>

        <div class="cv-body">
            <!-- Biografia -->
            <div class="cv-section">
                <h2><i class="far fa-user"></i> Perfil Ambiental</h2>
                <p class="bio"><?php echo !empty($user['bio_curriculo']) ? nl2br(htmlspecialchars($user['bio_curriculo'])) : "Nenhum currículo preenchido no perfil. Vá nas configurações do perfil para adicionar sua biografia verde!"; ?></p>
            </div>

            <!-- Conquistas de Engajamento -->
            <div class="cv-section">
                <h2><i class="fas fa-medal"></i> Estatísticas de Contribuição</h2>
                <div class="cv-stats">
                    <div class="cv-stat-card">
                        <i class="fas fa-map-marked-alt"></i>
                        <div>
                            <h3>Pontos Mapeados</h3>
                            <h4><?php echo $pontos_mapeados; ?> cadastrados</h4>
                        </div>
                    </div>
                    
                    <div class="cv-stat-card">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>
                            <h3>Reports de Conservação</h3>
                            <h4><?php echo $reports_enviados; ?> enviados</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cursos/Conhecimentos Verificados -->
            <div class="cv-section">
                <h2><i class="fas fa-graduation-cap"></i> Conhecimentos Verificados (Cursos & Quizzes)</h2>
                <?php if(count($cursos) === 0): ?>
                    <p style="color:#777; font-size:0.95rem; font-style:italic;">Nenhum quiz respondido ainda. Acesse os guias educacionais da comunidade para obter conhecimentos verificados e certificar suas habilidades ecológicas!</p>
                <?php else: ?>
                    <?php foreach($cursos as $c): ?>
                        <div class="course-item">
                            <div class="course-info">
                                <h4><?php echo htmlspecialchars($c['titulo']); ?></h4>
                                <span class="category"><?php echo htmlspecialchars($c['categoria']); ?></span>
                            </div>
                            <span class="course-date">Concluído em <?php echo date('d/m/Y', strtotime($c['data_conclusao'])); ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
