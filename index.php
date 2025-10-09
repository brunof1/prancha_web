<?php
// Inclui o script responsável pelo processamento da instalação
require_once 'includes/instalador.php';

// Verifica se o sistema já foi instalado anteriormente
if (file_exists('instalado.flag')) {
    // Se já estiver instalado, redireciona para a página de login
    header('Location: pages/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalação Inicial - Prancha Web</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <img src="imagens/logo.svg" alt="Logo Prancha Web" class="logo">
        <h2>Instalação Inicial</h2>

        <!-- Exibe mensagem de erro, se houver -->
        <?php if (!empty($mensagem)) echo "<p style='color:red;'>$mensagem</p>"; ?>

        <!-- Formulário para o usuário preencher os dados da instalação -->
        <form method="post">
            <input type="text" name="nome" placeholder="Nome do Administrador" required><br>
            <input type="email" name="email" placeholder="E-mail do Administrador" required><br>
            <input type="password" name="senha" placeholder="Senha do Administrador" required><br>
            <hr>
            <input type="text" name="host_banco" placeholder="Host do Banco de Dados (ex: localhost)" required><br>
            <input type="text" name="nome_banco" placeholder="Nome do Banco de Dados" required><br>
            <input type="text" name="usuario_banco" placeholder="Usuário do Banco de Dados" required><br>
            <input type="password" name="senha_banco" placeholder="Senha do Banco de Dados"><br><br>

            <button type="submit">Instalar</button>
        </form>
    </div>
</body>
</html>
