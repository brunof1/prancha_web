<?php include '../includes/cabecalho.php'; ?>
<?php require_once '../includes/modelo_cartoes.php'; ?>

<h2>Gerenciar Cartões</h2>

<p>
    <a class="botao-acao" href="criar_grupo_cartao.php">➕ Criar novo grupo de cartões</a> |
    <a class="botao-acao" href="criar_cartao.php">➕ Criar novo cartão</a>
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
                <strong><?php echo htmlspecialchars($grupo['nome']); ?></strong>
                - <a class="botao-acao" href="listar_cartoes_grupo.php?id=<?php echo $grupo['id']; ?>">👁️ Visualizar</a> |
                <a class="botao-acao" href="editar_grupo_cartao.php?id=<?php echo $grupo['id']; ?>">✏️ Editar</a> |
                <a class="botao-acao excluir" href="../includes/controle_excluir_grupo.php?id=<?php echo $grupo['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este grupo?');">🗑️ Excluir</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Nenhum grupo de cartões cadastrado ainda.</p>
<?php endif; ?>

<hr>

<h3>Todos os Cartões Cadastrados</h3>

<?php
$cartoes = listarTodosCartoes();
?>

<?php if (count($cartoes) > 0): ?>
    <div class="lista-cartoes">
        <?php foreach ($cartoes as $cartao): ?>
            <div class="cartao-item">
                <img src="../imagens/cartoes/<?php echo htmlspecialchars($cartao['imagem']); ?>" alt="<?php echo htmlspecialchars($cartao['texto_alternativo']); ?>"><br>
                <strong><?php echo htmlspecialchars($cartao['titulo']); ?></strong><br>
                <button class="botao-acao" type="button" onclick="falar('<?php echo addslashes($cartao['titulo']); ?>')"><span aria-hidden="true">🗣️</span> Falar</button>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>Nenhum cartão cadastrado ainda.</p>
<?php endif; ?>

<script src="../assets/js/falar.js"></script>
<?php include '../includes/rodape.php'; ?>
