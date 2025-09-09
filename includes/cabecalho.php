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
                <a href="dashboard.php" class="botao-acao">🏠 Início</a>
                <a href="gerenciar_pranchas.php" class="botao-acao">📋 Pranchas</a>
                <a href="gerenciar_cartoes.php" class="botao-acao">🖼️ Cartões</a>
                <a href="configuracoes.php" class="botao-acao">⚙️ Configurações</a>
            </nav>

            <!-- Botão de alternância de tema -->
            <button type="button" id="toggle-tema" class="botao-acao" aria-label="Alternar tema"></button>

            <form action="../includes/logout.php" method="post" class="form-sair">
            <button type="submit" class="botao-acao">🚪 Sair</button>
            </form>

        </div>
        <hr>

        <!-- carrega o JS do tema -->
        <script src="../assets/js/tema.js"></script>
