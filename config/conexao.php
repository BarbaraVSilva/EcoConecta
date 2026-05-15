<?php
// Dados do InfinityFree (Servidor de Produção)
$host = 'sql201.infinityfree.com';
$dbname = 'if0_41931839_ecoconecta';
$user = 'if0_41931839'; 
$pass = 'YVyulvX9myI7';

// Conexão usando MySQLi (Requisito acadêmico)
$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Erro na conexão com o banco de dados: " . mysqli_connect_error());
}

// Configura o charset para evitar problemas com acentuação
mysqli_set_charset($conn, "utf8");
?>
