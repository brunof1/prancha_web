<?php include '../includes/cabecalho.php'; ?>
<?php require_once '../includes/controle_grupos_cartoes.php'; ?>

<h2>Gerenciar Cartões</h2>

<a href="criar_grupo_cartao.php">➕ Criar Novo Grupo de Cartões</a>

<hr>

<?php if (count($lista_grupos) > 0): ?>
    <ul>
        <?php foreach ($lista_grupos as $grupo): ?>
            <li>
                <strong><?php echo htmlspecialchars($grupo['nome']); ?></strong> - 
                <a href="editar_grupo_cartao.php?id=<?php echo $grupo['id']; ?>">✏️ Editar</a> | 
                <a href="../includes/controle_excluir_grupo.php?id=<?php echo $grupo['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este grupo?');">🗑️ Excluir</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Nenhum grupo de cartões cadastrado ainda.</p>
<?php endif; ?>

<?php include '../includes/rodape.php'; ?>
