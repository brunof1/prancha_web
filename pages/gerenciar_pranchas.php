<?php include '../includes/cabecalho.php'; ?>
<?php require_once '../includes/controle_grupos_pranchas.php'; ?>
<?php require_once '../includes/controle_pranchas.php'; ?>

<!-- Botões de ação no topo -->
<p>
    <a href="criar_grupo_prancha.php">➕ Criar novo grupo de prancha</a> | 
    <a href="criar_prancha.php">➕ Criar nova prancha</a>
</p>

<h2>Gerenciar Grupos de Pranchas</h2>

<?php if (count($lista_grupos_pranchas) > 0): ?>
    <ul>
        <?php foreach ($lista_grupos_pranchas as $grupo): ?>
            <li>
                <?php echo htmlspecialchars($grupo['nome']); ?> - 
                <a href="editar_grupo_prancha.php?id=<?php echo $grupo['id']; ?>">✏️ Editar</a> |
                <a href="../includes/controle_excluir_grupo_prancha.php?id=<?php echo $grupo['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este grupo?');">🗑️ Excluir</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Nenhum grupo de prancha cadastrado ainda.</p>
<?php endif; ?>

<hr>

<h2>Gerenciar Pranchas</h2>

<?php if (isset($_GET['sucesso'])) echo "<p style='color:green;'>Prancha excluída com sucesso.</p>"; ?>
<?php if (isset($_GET['erro'])) echo "<p style='color:red;'>Erro ao excluir a prancha.</p>"; ?>

<?php if (count($lista_pranchas) > 0): ?>
    <ul>
        <?php foreach ($lista_pranchas as $prancha): ?>
            <li>
                <?php echo htmlspecialchars($prancha['nome']); ?>
                - <a href="editar_prancha.php?id=<?php echo $prancha['id']; ?>">✏️ Editar</a> |
                <a href="../includes/controle_excluir_prancha.php?id=<?php echo $prancha['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir esta prancha?');">🗑️ Excluir</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Nenhuma prancha cadastrada.</p>
<?php endif; ?>

<?php include '../includes/rodape.php'; ?>
