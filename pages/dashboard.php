<?php include '../includes/cabecalho.php'; ?>

<h2>Dashboard</h2>
<p>Bem-vindo, <?php echo htmlspecialchars($_SESSION['nome_usuario']); ?>!</p>
<p>Tipo de usuário: <?php echo htmlspecialchars($_SESSION['tipo_usuario']); ?></p>

<p>Escolha uma opção no menu acima para começar.</p>

<?php include '../includes/rodape.php'; ?>
