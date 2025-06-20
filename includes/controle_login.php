<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once '../config/config.php';
require_once 'funcoes.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = limparEntrada($_POST['email']);
    $senha = limparEntrada($_POST['senha']);

    // Conexão com o banco
    $conexao = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);

    if ($conexao->connect_error) {
        die("Erro ao conectar ao banco: " . $conexao->connect_error);
    }

    // Prepara a consulta SQL
    $sql = "SELECT id, nome, senha, tipo FROM usuarios WHERE email = ?";
    $comando = $conexao->prepare($sql);
    $comando->bind_param("s", $email);
    $comando->execute();

    // Faz o bind dos resultados
    $comando->bind_result($id_usuario, $nome_usuario, $senha_hash, $tipo_usuario);

    // Busca os resultados
    if ($comando->fetch()) {
        // Verifica a senha
        if (password_verify($senha, $senha_hash)) {
            // Login válido → cria a sessão
            $_SESSION['id_usuario'] = $id_usuario;
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
?>
