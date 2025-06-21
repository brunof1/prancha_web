<?php
session_start();

// Protege as páginas: só permite acesso se o usuário estiver logado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prancha Web</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <img src="../imagens/logo.png" alt="Logo Prancha Web" class="logo">
        <div class="menu-superior">
            <nav>
                <a href="dashboard.php">🏠 Início</a> |
                <a href="gerenciar_pranchas.php">📋 Pranchas</a> |
                <a href="gerenciar_cartoes.php">🖼️ Cartões</a> |
                <a href="configuracoes.php">⚙️ Configurações</a>
            </nav>
            <form action="../includes/logout.php" method="post" class="form-sair">
                <button type="submit" class="botao-sair">🚪 Sair</button>
            </form>
        </div>
        <hr>
