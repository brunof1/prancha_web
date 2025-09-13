<?php
// includes/conexao.php
require_once __DIR__ . '/../config/config.php';

$conn = @new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
if ($conn->connect_error) {
    die('Erro de conexão: ' . $conn->connect_error);
}

$conn->set_charset('utf8mb4');
@$conn->query("SET collation_connection = 'utf8mb4_unicode_ci'");
?>