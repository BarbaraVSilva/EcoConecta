<?php
// Tenta conectar ao banco de produção. Se falhar ou se estiver em localhost, tenta o local.
$is_localhost = false;
if (isset($_SERVER['SERVER_NAME'])) {
    $host_name = $_SERVER['SERVER_NAME'];
    if ($host_name === 'localhost' || $host_name === '127.0.0.1' || strpos($host_name, '192.168.') === 0) {
        $is_localhost = true;
    }
} else {
    // Fallback para execução CLI
    $is_localhost = true;
}

if ($is_localhost) {
    $host = 'localhost';
    $dbname = 'ecoconecta'; // banco local padrão
    $user = 'root';
    $pass = ''; // senha padrão vazia para XAMPP/WAMP
} else {
    // Dados do InfinityFree (Servidor de Produção)
    $host = 'sql201.infinityfree.com';
    $dbname = 'if0_41931839_ecoconecta';
    $user = 'if0_41931839'; 
    $pass = 'YVyulvX9myI7';
}

// Conexão usando MySQLi (Requisito acadêmico)
// Usamos @ para silenciar warnings temporários caso a conexão falhe e queiramos tentar o fallback
$conn = @mysqli_connect($host, $user, $pass, $dbname);

if (!$conn && !$is_localhost) {
    // Se a conexão remota de produção falhar (ex: bloqueio de IP externo no InfinityFree), 
    // tenta conectar ao banco local como último recurso
    $host = 'localhost';
    $dbname = 'ecoconecta';
    $user = 'root';
    $pass = '';
    $conn = mysqli_connect($host, $user, $pass, $dbname);
}

if (!$conn) {
    die("Erro na conexão com o banco de dados: " . mysqli_connect_error());
}

// Configura o charset para evitar problemas com acentuação
mysqli_set_charset($conn, "utf8");
?>
