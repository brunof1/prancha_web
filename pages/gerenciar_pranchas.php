<?php include '../includes/cabecalho.php'; ?>
<?php require_once '../includes/controle_pranchas.php'; ?>

<h2>Gerenciar Pranchas</h2>

<?php
if (isset($_GET['sucesso'])) echo "<p style='color:green;'>Prancha excluída com sucesso.</p>";
if (isset($_GET['erro'])) echo "<p style='color:red;'>Erro ao excluir a prancha.</p>";
?>

<?php if (count($lista_pranchas) > 0): ?>
    <ul>
        <?php foreach ($lista_pranchas as $prancha): ?>
            <li>
                <?php echo htmlspecialchars($prancha['nome']); ?> -
                <a href="editar_prancha.php?id=<?php echo $prancha['id']; ?>">✏️ Editar</a> |
                <a href="../includes/controle_excluir_prancha.php?id=<?php echo $prancha['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir esta prancha?');">🗑️ Excluir</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Nenhuma prancha cadastrada ainda.</p>
<?php endif; ?>

<p><a href="nova_prancha.php">➕ Criar nova prancha</a></p>

<?php include '../includes/rodape.php'; ?>
