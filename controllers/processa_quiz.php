<?php
session_start();
require_once '../config/conexao.php';
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_perfil'] !== 'cidadao') {
    echo json_encode(['success' => false, 'message' => 'Faça login como cidadão para responder aos quizzes e acumular EcoPontos.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guia_id']) && isset($_POST['resposta'])) {
    $guia_id = intval($_POST['guia_id']);
    $resposta = strtoupper(trim($_POST['resposta']));
    $usuario_id = $_SESSION['usuario_id'];

    // 1. Busca resposta correta do quiz
    $stmt_q = $conn->prepare("SELECT opcao_correta FROM guia_quizzes WHERE guia_id = ?");
    $stmt_q->bind_param("i", $guia_id);
    $stmt_q->execute();
    $quiz = $stmt_q->get_result()->fetch_assoc();

    if (!$quiz) {
        echo json_encode(['success' => false, 'message' => 'Quiz não encontrado para este artigo.']);
        exit;
    }

    // 2. Valida resposta
    if ($resposta !== $quiz['opcao_correta']) {
        echo json_encode(['success' => false, 'message' => 'Resposta incorreta! Releia o artigo com atenção e tente novamente. 🌿']);
        exit;
    }

    // 3. Verifica se já respondeu
    $stmt_chk = $conn->prepare("SELECT id FROM usuario_quizzes WHERE usuario_id = ? AND guia_id = ?");
    $stmt_chk->bind_param("ii", $usuario_id, $guia_id);
    $stmt_chk->execute();
    $concluido = $stmt_chk->get_result()->num_rows > 0;

    if ($concluido) {
        echo json_encode(['success' => false, 'message' => 'Você já respondeu a este quiz e recebeu seus EcoPontos anteriormente.']);
        exit;
    }

    // 4. Salva no banco de dados e credita os pontos
    $conn->begin_transaction();
    try {
        // Credita 15 pontos
        $add_points = $conn->prepare("UPDATE usuarios SET eco_pontos = eco_pontos + 15 WHERE id = ?");
        $add_points->bind_param("i", $usuario_id);
        $add_points->execute();

        // Salva registro de conclusão
        $save_completion = $conn->prepare("INSERT INTO usuario_quizzes (usuario_id, guia_id) VALUES (?, ?)");
        $save_completion->bind_param("ii", $usuario_id, $guia_id);
        $save_completion->execute();

        $conn->commit();

        echo json_encode([
            'success' => true,
            'message' => 'Parabéns! Resposta corretíssima! Você ganhou +15 EcoPontos pela sua leitura consciente. 🌟'
        ]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Ocorreu um erro ao creditar seus pontos. Tente novamente.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Requisição inválida.']);
}
?>
