<?php
// Tenta conectar ao banco de produção. Se falhar ou se estiver em localhost, tenta o local.
$is_localhost = false;
if (isset($_SERVER['SERVER_NAME'])) {
    $host_name = $_SERVER['SERVER_NAME'];
    if ($host_name === 'localhost' || 
        $host_name === '127.0.0.1' || 
        $host_name === '::1' || 
        strpos($host_name, '192.168.') === 0 ||
        strpos($host_name, '10.') === 0 ||
        strpos($host_name, '172.') === 0) {
        $is_localhost = true;
    }
} else {
    // Fallback para execução CLI
    $is_localhost = true;
}

// Desativa temporariamente o lançamento de exceções do mysqli para tratamento amigável de erros
$driver = new mysqli_driver();
$default_report_mode = $driver->report_mode;
mysqli_report(MYSQLI_REPORT_OFF);

$conn = false;

if ($is_localhost) {
    $host = 'localhost';
    $dbname = 'ecoconecta'; // banco local padrão
    $user = 'root';
    $pass = ''; // senha padrão vazia para XAMPP/WAMP
    $conn = @mysqli_connect($host, $user, $pass, $dbname);
} else {
    // Dados do Servidor de Produção (Ex: InfinityFree)
    $host = 'SEU_HOST_PRODUCAO';
    $dbname = 'SEU_BANCO_PRODUCAO';
    $user = 'SEU_USUARIO_PRODUCAO'; 
    $pass = 'SUA_SENHA_PRODUCAO';
    $conn = @mysqli_connect($host, $user, $pass, $dbname);
}

// Se a conexão remota de produção falhar, tenta conectar ao banco local como último recurso
if (!$conn && !$is_localhost) {
    $host = 'localhost';
    $dbname = 'ecoconecta';
    $user = 'root';
    $pass = '';
    $conn = @mysqli_connect($host, $user, $pass, $dbname);
}

// Restaura a configuração de relatório padrão do mysqli
mysqli_report($default_report_mode);

if (!$conn) {
    die("<h3>Erro na conexão com o banco de dados.</h3>" .
        "<p>Não foi possível estabelecer contato com o banco de dados. Por favor, certifique-se de que:</p>" .
        "<ul>" .
        "<li>O painel do <strong>XAMPP / WAMP</strong> está aberto.</li>" .
        "<li>O serviço <strong>MySQL</strong> está ativado (botão 'Start' no MySQL).</li>" .
        "<li>O banco de dados local com o nome <strong>ecoconecta</strong> foi devidamente criado e importado.</li>" .
        "</ul>" .
        "<p><em>Erro técnico: " . mysqli_connect_error() . "</em></p>");
}

// Configura o charset para evitar problemas com acentuação
mysqli_set_charset($conn, "utf8");
?>
