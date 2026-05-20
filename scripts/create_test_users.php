<?php
// Script para criar/reinicializar os usuários de teste no EcoConecta
require_once __DIR__ . '/../config/conexao.php';

$hash = '$2y$10$NqXm0MQPkLDppU5CNAIUnufw8YRzEN/IPYQOk59WzmVyXwcrcNLES'; // hash para a senha 'teste123'

// 1. Cidadão de Teste
$email_cidadao = 'cidadao@teste.com';
mysqli_query($conn, "DELETE FROM usuarios WHERE email = '$email_cidadao'");
$q1 = "INSERT INTO usuarios (id, nome, email, senha_hash, tipo_perfil, eco_pontos, bio_curriculo, aceite_lgpd) 
       VALUES (8, 'Cidadão de Teste', '$email_cidadao', '$hash', 'cidadao', 500, 'Perfil cidadão para testes do sistema.', 1)";
if (mysqli_query($conn, $q1)) {
    echo "[OK] Cidadão de Teste cadastrado (e-mail: cidadao@teste.com / senha: teste123)\n";
} else {
    echo "[ERRO] Erro ao cadastrar Cidadão de Teste: " . mysqli_error($conn) . "\n";
}

// 2. Empresa de Teste
$email_empresa = 'empresa@teste.com';
mysqli_query($conn, "DELETE FROM usuarios WHERE email = '$email_empresa'");
$q2 = "INSERT INTO usuarios (id, nome, email, senha_hash, tipo_perfil, eco_pontos, bio_curriculo, aceite_lgpd) 
       VALUES (9, 'Empresa de Teste', '$email_empresa', '$hash', 'empresa', 0, 'Perfil empresa para testes do sistema.', 1)";
if (mysqli_query($conn, $q2)) {
    echo "[OK] Empresa de Teste cadastrada (e-mail: empresa@teste.com / senha: teste123)\n";
} else {
    echo "[ERRO] Erro ao cadastrar Empresa de Teste: " . mysqli_error($conn) . "\n";
}
?>
