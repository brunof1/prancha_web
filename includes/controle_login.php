<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once '../config/config.php';
require_once 'funcoes.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = limparEntrada($_POST['email']);
    $senha = limparEntrada($_POST['senha']);

    $conexao = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
    if ($conexao->connect_error) {
        die("Erro ao conectar ao banco: " . $conexao->connect_error);
    }

    // UTF-8 na conexão
    $conexao->set_charset('utf8mb4');
    @$conexao->query("SET collation_connection = 'utf8mb4_unicode_ci'");

    $sql = "SELECT id, nome, senha, tipo FROM usuarios WHERE email = ?";
    $comando = $conexao->prepare($sql);
    $comando->bind_param("s", $email);
    $comando->execute();
    $comando->bind_result($id_usuario, $nome_usuario, $senha_hash, $tipo_usuario);

    if ($comando->fetch()) {
        if (password_verify($senha, $senha_hash)) {
            // Regenera antes de popular (robustez)
            if (function_exists('session_regenerate_id')) { session_regenerate_id(true); }

            // NENHUMA reconversão aqui: já vem em UTF-8 se a conexão está OK
            $_SESSION['id_usuario']   = $id_usuario;
            $_SESSION['nome_usuario'] = $nome_usuario;
            $_SESSION['tipo_usuario'] = $tipo_usuario;

            header('Location: ../pages/dashboard.php');
            exit;
        } else {
            echo "Senha incorreta.";
        }
    } else {
        echo "Usuário não encontrado.";
    }

    $comando->close();
    $conexao->close();
} else {
    echo "Requisição inválida.";
}
