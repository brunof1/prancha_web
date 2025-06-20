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
</head>
<body>
    <div class="login-container">
        <img src="../imagens/logo.png" alt="Logo Prancha Web" class="logo">
        <h2>Login</h2>

        <!-- Aqui futuramente pode ter mensagem de erro, se quiser -->
        <?php if (isset($_GET['erro']) && $_GET['erro'] == 1) echo "<p style='color:red;'>Usuário ou senha inválidos.</p>"; ?>

        <form action="../includes/controle_login.php" method="post">
            <input type="text" name="email" placeholder="Email" required><br>
            <input type="password" name="senha" placeholder="Senha" required><br>
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
