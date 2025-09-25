<?php
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
