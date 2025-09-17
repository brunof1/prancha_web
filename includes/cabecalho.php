<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/conexao.php';

$idUsuario = (int) $_SESSION['id_usuario'];
$tema = 'light';

// Tema
if ($stmt = $conn->prepare("SELECT tema_preferido FROM usuarios WHERE id = ? LIMIT 1")) {
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $stmt->bind_result($temaDB);
    if ($stmt->fetch() && in_array($temaDB, ['light','dark'], true)) {
        $tema = $temaDB;
    }
    $stmt->close();
}

// Reforça nome/tipo se faltarem, sem tentar reencodar
if (empty($_SESSION['nome_usuario']) || empty($_SESSION['tipo_usuario'])) {
    if ($stmt2 = $conn->prepare("SELECT nome, tipo FROM usuarios WHERE id = ? LIMIT 1")) {
        $stmt2->bind_param("i", $idUsuario);
        $stmt2->execute();
        $stmt2->bind_result($nomeDB, $tipoDB);
        if ($stmt2->fetch()) {
            $_SESSION['nome_usuario'] = $nomeDB;
            $_SESSION['tipo_usuario'] = $tipoDB;
        }
        $stmt2->close();
    }
}

$isAdminNav = (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prancha Web</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- Regras de acessibilidade sem alterar a paleta -->
    <link rel="stylesheet" href="../assets/css/wcag.css">
</head>
<body data-theme="<?= htmlspecialchars($tema, ENT_QUOTES, 'UTF-8') ?>">

    <!-- Skip link para WCAG 2.4.1 -->
    <a href="#conteudo" class="skip-link">Pular para o conteúdo</a>

    <div class="container">
        <img src="../imagens/logo.png" alt="Logo Prancha Web" class="logo">
        <div class="menu-superior">
            <nav aria-label="Navegação principal">
                <a href="dashboard.php" class="botao-acao">🏠 Início</a>
                <a href="gerenciar_cartoes.php" class="botao-acao">🖼️ Cartões</a>
                <a href="gerenciar_pranchas.php" class="botao-acao">📋 Pranchas</a>
                <?php if ($isAdminNav): ?>
                  <a href="gerenciar_usuarios.php" class="botao-acao">👥 Usuários</a>
                <?php endif; ?>
                <a href="configuracoes.php" class="botao-acao">⚙️ Configurações</a>
            </nav>

            <!-- Botão de alternância de tema com nome acessível e alvo 44x44 -->
            <button
                type="button"
                id="toggle-tema"
                class="botao-acao"
                aria-label="Alternar tema claro/escuro"
                aria-pressed="<?= $tema === 'dark' ? 'true' : 'false' ?>">
                <span aria-hidden="true">🌓</span>
                <span class="sr-only">Alternar tema claro/escuro</span>
            </button>

            <form action="../includes/logout.php" method="post" class="form-sair">
                <button type="submit" class="botao-acao">🚪 Sair</button>
            </form>

        </div>
        <hr>

        <!-- carrega o JS do tema -->
        <script src="../assets/js/tema.js"></script>

        <!-- Conteúdo principal começa aqui (para skip-link e leitores de tela) -->
        <main id="conteudo" tabindex="-1">
