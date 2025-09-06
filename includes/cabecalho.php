<?php

session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

// carrega conexão + tema do usuário
require_once __DIR__ . '/conexao.php';

$idUsuario = (int) $_SESSION['id_usuario'];
$tema = 'light';

if ($stmt = $conn->prepare("SELECT tema_preferido FROM usuarios WHERE id = ? LIMIT 1")) {
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $stmt->bind_result($temaDB);
    if ($stmt->fetch() && in_array($temaDB, ['light','dark'], true)) {
        $tema = $temaDB;
    }
    $stmt->close();
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
<body data-theme="<?= htmlspecialchars($tema, ENT_QUOTES, 'UTF-8') ?>">
    <div class="container">
        <img src="../imagens/logo.png" alt="Logo Prancha Web" class="logo">
        <div class="menu-superior">
            <nav>
                <a class="acao-link" href="dashboard.php">🏠 Início</a><span aria-hidden="true"> | </span>
                <a class="acao-link" href="gerenciar_pranchas.php">📋 Pranchas</a><span aria-hidden="true"> | </span>
                <a class="acao-link" href="gerenciar_cartoes.php">🖼️ Cartões</a>
            </nav>

            <!-- Botão de alternância de tema -->
            <button type="button" id="toggle-tema" class="botao-tema" aria-label="Alternar tema"></button>

            <form action="../includes/logout.php" method="post" class="form-sair">
                <button type="submit" class="botao-sair">🚪 Sair</button>
            </form>
        </div>
        <hr>

        <!-- carrega o JS do tema -->
        <script src="../assets/js/tema.js"></script>
