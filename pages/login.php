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
require_once '../config/config.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Prancha Web</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/wcag.css">
</head>
<body>
    <a href="#conteudo" class="skip-link">Pular para o conteúdo</a>

    <main id="conteudo" tabindex="-1">
        <div class="login-container">
            <img src="../imagens/logo.svg" alt="Logo Prancha Web" class="logo">
            <h2>Login</h2>

            <?php if (isset($_GET['erro']) && $_GET['erro'] == 1) echo "<p role=\"alert\" aria-live=\"polite\" style='color:red;'>Usuário ou senha inválidos.</p>"; ?>

            <form action="../includes/controle_login.php" method="post" novalidate>
                <div class="campo">
                    <label for="email">Email</label><br>
                    <input id="email" type="email" name="email" placeholder="seu@exemplo.com" required aria-required="true" autocomplete="username">
                </div>

                <div class="campo">
                    <label for="senha">Senha</label><br>
                    <input id="senha" type="password" name="senha" placeholder="Sua senha" required aria-required="true" autocomplete="current-password">
                </div>

                <button type="submit" class="botao-acao">Entrar</button>
            </form>
        </div>
    </main>
</body>
</html>
