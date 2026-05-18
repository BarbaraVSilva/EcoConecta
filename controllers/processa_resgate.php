<?php
session_start();
require_once '../config/conexao.php';
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_perfil'] !== 'cidadao') {
    echo json_encode(['success' => false, 'message' => 'Faça login como cidadão para resgatar recompensas.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recompensa_id'])) {
    $recompensa_id = intval($_POST['recompensa_id']);
    $usuario_id = $_SESSION['usuario_id'];

    // 1. Obtém dados da recompensa
    $stmt_rec = $conn->prepare("SELECT custo_pontos, quantidade_disponivel FROM recompensas WHERE id = ?");
    $stmt_rec->bind_param("i", $recompensa_id);
    $stmt_rec->execute();
    $recompensa = $stmt_rec->get_result()->fetch_assoc();

    if (!$recompensa) {
        echo json_encode(['success' => false, 'message' => 'Recompensa não encontrada.']);
        exit;
    }

    if (intval($recompensa['quantidade_disponivel']) <= 0) {
        echo json_encode(['success' => false, 'message' => 'Recompensa esgotada.']);
        exit;
    }

    $custo = intval($recompensa['custo_pontos']);

    // 2. Obtém pontos do usuário
    $stmt_usr = $conn->prepare("SELECT eco_pontos FROM usuarios WHERE id = ?");
    $stmt_usr->bind_param("i", $usuario_id);
    $stmt_usr->execute();
    $usuario = $stmt_usr->get_result()->fetch_assoc();

    $pontos_atuais = intval($usuario['eco_pontos']);

    if ($pontos_atuais < $custo) {
        echo json_encode(['success' => false, 'message' => 'Saldo de EcoPontos insuficiente para este resgate.']);
        exit;
    }

    // 3. Gera código de cupom único e seguro
    $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $coupon_rand = "";
    for ($i = 0; $i < 6; $i++) {
        $coupon_rand .= $chars[rand(0, strlen($chars) - 1)];
    }
    $codigo_cupom = "ECO-" . $coupon_rand . "-" . rand(1000, 9999);

    // 4. Executa transações no banco
    $conn->begin_transaction();

    try {
        // Reduz os pontos do cidadão
        $deduz_pontos = $conn->prepare("UPDATE usuarios SET eco_pontos = eco_pontos - ? WHERE id = ?");
        $deduz_pontos->bind_param("ii", $custo, $usuario_id);
        $deduz_pontos->execute();

        // Reduz a quantidade disponível da recompensa
        $deduz_estoque = $conn->prepare("UPDATE recompensas SET quantidade_disponivel = quantidade_disponivel - 1 WHERE id = ?");
        $deduz_estoque->bind_param("i", $recompensa_id);
        $deduz_estoque->execute();

        // Insere o resgate
        $insere_resgate = $conn->prepare("INSERT INTO resgates (usuario_id, recompensa_id, codigo_cupom, status) VALUES (?, ?, ?, 'ativo')");
        $insere_resgate->bind_param("iis", $usuario_id, $recompensa_id, $codigo_cupom);
        $insere_resgate->execute();

        $conn->commit();

        echo json_encode([
            'success' => true,
            'coupon' => $codigo_cupom
        ]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Ocorreu um erro no processamento do resgate. Tente novamente.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Requisição inválida.']);
}
?>
