<?php

/**
 * Prancha Web
 * Plataforma Web de Comunicação Alternativa e Aumentativa (CAA)
 *
 * Copyright (c) 2025 Bruno Silva da Silva
 *
 * Este arquivo faz parte do projeto Prancha Web.
 *
 * Licenciamento duplo:
 * - Apache License 2.0
 * - GNU General Public License v3.0 (GPLv3)
 *
 * Você pode redistribuir e/ou modificar este arquivo sob os termos de
 * qualquer uma das licenças, a seu critério, desde que cumpra integralmente
 * os respectivos requisitos.
 *
 * Você deve ter recebido uma cópia das licenças junto com este programa.
 * Caso contrário, veja:
 * - https://www.apache.org/licenses/LICENSE-2.0
 * - https://www.gnu.org/licenses/gpl-3.0.html
 */

session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/modelo_preferencias.php';

$idUsuario = (int) $_SESSION['id_usuario'];
$tema = 'light';

/* Tema */
if ($stmt = $conn->prepare("SELECT tema_preferido FROM usuarios WHERE id = ? LIMIT 1")) {
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $stmt->bind_result($temaDB);
    if ($stmt->fetch() && in_array($temaDB, ['light','dark'], true)) {
        $tema = $temaDB;
    }
    $stmt->close();
}

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

$prefs = obterPreferenciasUsuario($idUsuario);
$fontPx = isset($prefs['font_base_px']) ? (int)$prefs['font_base_px'] : 16;
$fontPx = max(14, min(24, $fontPx));

$isAdminNav = (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin');
?>
<!DOCTYPE html>
<html lang="pt-BR" style="--base-font-size: <?= htmlspecialchars((string)$fontPx, ENT_QUOTES, 'UTF-8') ?>px">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prancha Web</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- Regras de acessibilidade -->
    <link rel="stylesheet" href="../assets/css/wcag.css">

</head>
<body data-theme="<?= htmlspecialchars($tema, ENT_QUOTES, 'UTF-8') ?>">
    <!-- Skip link para WCAG 2.4.1 -->
    <a href="#conteudo" class="skip-link">Pular para o conteúdo</a>

    <div class="container">
        <img src="../imagens/logo.svg" alt="Logo Prancha Web" class="logo">
        <div class="menu-superior">
            <nav class="nav-wrap" aria-label="Navegação principal">
                <details class="menu-drop">
                    <summary class="botao-acao" aria-expanded="false">📖 Menu</summary>
                    <div class="menu-drop__panel">
                        <a href="dashboard.php" class="botao-acao menu-link">🏠 Início</a>
                        <a href="gerenciar_cartoes.php" class="botao-acao menu-link">🖼️ Cartões</a>
                        <a href="gerenciar_pranchas.php" class="botao-acao menu-link">📋 Pranchas</a>
                        <?php if ($isAdminNav): ?>
                            <a href="gerenciar_usuarios.php" class="botao-acao menu-link">👥 Usuários</a>
                        <?php endif; ?>
                        <a href="bateria_social.php" class="botao-acao menu-link">🔋 Bateria</a>
                        <?php if ($isAdminNav): ?>
                            <a href="bateria_social_admin.php" class="botao-acao menu-link">📊 Bateria (Admin)</a>
                        <?php endif; ?>
                        <a href="configuracoes.php" class="botao-acao menu-link">⚙️ Configurações</a>
                    </div>
                </details>
            </nav>

            <!-- Botão de alternância de tema -->
            <button
                type="button"
                id="toggle-tema"
                class="botao-acao"
                aria-label="Alternar tema claro/escuro"
                aria-pressed="<?= $tema === 'dark' ? 'true' : 'false' ?>">
                <span aria-hidden="true">🌓</span>
                <span class="sr-only">Alternar tema claro/escuro</span>
            </button>

            <!-- Link para o Manual/FAQ -->
             <a href="manual.php" class="botao-acao" aria-label="Abrir Manual e Perguntas Frequentes">❓ Manual/FAQ</a>

            <form action="../includes/logout.php" method="post" class="form-sair">
                <button type="submit" class="botao-acao">🚪 Sair</button>
            </form>

        </div>
        <hr>

        <!-- carrega o JS do tema -->
        <script src="../assets/js/tema.js"></script>

        <script src="../assets/js/drop.js"></script>

        <!-- Conteúdo principal -->
        <main id="conteudo" tabindex="-1">
