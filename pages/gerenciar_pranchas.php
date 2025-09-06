<?php include '../includes/cabecalho.php'; ?>
<?php require_once '../includes/controle_grupos_pranchas.php'; ?>
<?php require_once '../includes/controle_pranchas.php'; ?>

<!-- Botões de ação no topo -->
<p>
    <a class="acao-link" href="criar_grupo_prancha.php">➕ Criar novo grupo de prancha</a> | 
    <a class="acao-link" href="criar_prancha.php">➕ Criar nova prancha</a>
</p>

<h2>Gerenciar Grupos de Pranchas</h2>

<?php if (count($lista_grupos_pranchas) > 0): ?>
    <ul>
        <?php foreach ($lista_grupos_pranchas as $grupo): ?>
            <li>
                <?php echo htmlspecialchars($grupo['nome']); ?> - 
                <a class="acao-link" href="editar_grupo_prancha.php?id=<?php echo $grupo['id']; ?>">✏️ Editar</a> |
                <a class="acao-link" href="../includes/controle_excluir_grupo_prancha.php?id=<?php echo $grupo['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este grupo?');">🗑️ Excluir</a>
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
            <?php
                $cartoes_ids = buscarCartoesDaPrancha($prancha['id']);
                $cartoes = buscarCartoesPorIds($cartoes_ids);
                $titulos_cartoes = array_column($cartoes, 'titulo');
            ?>
            <li style="margin-bottom: 10px;">
                <strong><?php echo htmlspecialchars($prancha['nome']); ?></strong>
                - <a class="acao-link" href="ver_prancha.php?id=<?php echo $prancha['id']; ?>">👁️ Visualizar</a> |
                <a class="acao-link" href="editar_prancha.php?id=<?php echo $prancha['id']; ?>">✏️ Editar</a> | 
                <a class="acao-link" href="../includes/controle_excluir_prancha.php?id=<?php echo $prancha['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir esta prancha?');" style="margin-left:10px; color:red;">🗑️ Excluir</a> | 
                <button type="button" class="acao-link" onclick='falarListaDeCartoes(<?= json_encode($titulos_cartoes) ?>)'><span aria-hidden="true">🗣️</span> Falar</button>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Nenhuma prancha cadastrada.</p>
<?php endif; ?>
<script src="../assets/js/falar.js"></script>
<?php include '../includes/rodape.php'; ?>