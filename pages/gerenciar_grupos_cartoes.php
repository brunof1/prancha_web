
<?php include '../includes/cabecalho.php'; ?>
<?php require_once '../includes/controle_grupos_cartoes.php'; ?>

<h2>Gerenciar Grupos de Cartões</h2>

<?php if (!empty($mensagem)) echo "<p style='color:red;'>$mensagem</p>"; ?>
<?php if (isset($_GET['sucesso'])) echo "<p style='color:green;'>Grupo criado com sucesso.</p>"; ?>

<ul>
    <?php foreach ($lista_grupos as $grupo): ?>
        <li>
            <?php echo htmlspecialchars($grupo['nome']); ?>
            - <a href="editar_grupo_cartao.php?id=<?php echo $grupo['id']; ?>">✏️ Editar</a> |
            <a href="../includes/controle_excluir_grupo.php?id=<?php echo $grupo['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este grupo?');">🗑️ Excluir</a>
        </li>
    <?php endforeach; ?>
</ul>

<h3>Criar Novo Grupo</h3>
<form method="post">
    <input type="text" name="nome_grupo" placeholder="Nome do grupo" required><br><br>
    <button type="submit">Criar Grupo</button>
</form>

<?php include '../includes/rodape.php'; ?>
