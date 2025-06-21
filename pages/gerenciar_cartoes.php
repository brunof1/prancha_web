<?php include '../includes/cabecalho.php'; ?>
<?php require_once '../includes/modelo_cartoes.php'; ?>

<h2>Gerenciar Cartões</h2>

<p>
    <a href="criar_grupo_cartao.php">➕ Criar novo grupo de cartões</a> |
    <a href="criar_cartao.php">➕ Criar novo cartão</a>
</p>

<?php
// Mensagens de feedback
if (isset($_GET['sucesso'])) echo "<p style='color:green;'>Cartão criado com sucesso.</p>";
if (isset($_GET['gsucesso'])) echo "<p style='color:green;'>Grupo criado com sucesso.</p>";
if (isset($_GET['gsucesso_excluir'])) echo "<p style='color:green;'>Grupo excluído com sucesso.</p>";
if (isset($_GET['sucesso_excluir'])) echo "<p style='color:green;'>Cartão excluído com sucesso.</p>";
if (isset($_GET['erro'])) echo "<p style='color:red;'>Erro ao excluir o grupo.</p>";
if (isset($_GET['erro_excluir'])) echo "<p style='color:red;'>Erro ao excluir o cartão.</p>";

// Lista de grupos
$grupos = listarGrupos();
?>

<?php if (count($grupos) > 0): ?>
    <h3>Grupos de Cartões Cadastrados</h3>
    <ul>
        <?php foreach ($grupos as $grupo): ?>
            <li>
                <a href="listar_cartoes_grupo.php?id=<?php echo $grupo['id']; ?>">
                    <?php echo htmlspecialchars($grupo['nome']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Nenhum grupo de cartões cadastrado ainda.</p>
<?php endif; ?>

<?php include '../includes/rodape.php'; ?>
