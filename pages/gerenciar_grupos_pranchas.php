<?php include '../includes/cabecalho.php'; ?>
<?php require_once '../includes/controle_grupos_pranchas.php'; ?>

<h2>Grupos de Pranchas</h2>

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

<p><a href="criar_grupo_prancha.php">➕ Criar novo grupo de prancha</a></p>

<?php include '../includes/rodape.php'; ?>
