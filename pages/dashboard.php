<?php include '../includes/cabecalho.php'; ?>

<h1>🏠 Início</h1>
<?php
    $nome_exibir = isset($_SESSION['nome_usuario']) ? $_SESSION['nome_usuario'] : '';
    $tipo_exibir = isset($_SESSION['tipo_usuario']) ? $_SESSION['tipo_usuario'] : '';
?>
<p>Bem-vindo, <?php echo htmlspecialchars($nome_exibir, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>!</p>
<p>Tipo de usuário: <?php echo htmlspecialchars($tipo_exibir, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></p>

<p>Escolha uma opção no menu acima para começar.</p>

<?php include '../includes/widget_bateria.php'; ?>

<?php include '../includes/rodape.php'; ?>
