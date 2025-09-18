<?php
include '../includes/cabecalho.php';
require_once '../includes/modelo_cartoes.php';

$isAdmin = ($_SESSION['tipo_usuario'] === 'admin');
?>

<h2><?php echo $isAdmin ? 'Gerenciar Cartões' : 'Cartões'; ?></h2>

<?php if ($isAdmin): ?>
<p>
    <a class="botao-acao" href="criar_grupo_cartao.php">➕ Criar novo grupo de cartões</a> |
    <a class="botao-acao" href="criar_cartao.php">➕ Criar novo cartão</a>
</p>
<?php endif; ?>

<?php
if ($isAdmin) {
    if (isset($_GET['sucesso'])) echo "<p style='color:green;'>Cartão criado com sucesso.</p>";
    if (isset($_GET['gsucesso'])) echo "<p style='color:green;'>Grupo criado com sucesso.</p>";
    if (isset($_GET['gsucesso_excluir'])) echo "<p style='color:green;'>Grupo excluído com sucesso.</p>";
    if (isset($_GET['sucesso_excluir'])) echo "<p style='color:green;'>Cartão excluído com sucesso.</p>";
    if (isset($_GET['erro'])) echo "<p style='color:red;'>Erro ao excluir o grupo.</p>";
    if (isset($_GET['erro_excluir'])) echo "<p style='color:red;'>Erro ao excluir o cartão.</p>";
    if (isset($_GET['edit_ok'])) echo "<p style='color:green;'>Cartão atualizado com sucesso.</p>";
    if (isset($_GET['erro_editar'])) echo "<p style='color:red;'>Erro ao atualizar o cartão.</p>";
}

$grupos = listarGrupos();
?>

<?php if (count($grupos) > 0): ?>
    <h3>Grupos de Cartões Cadastrados</h3>
    <ul>
        <?php foreach ($grupos as $grupo): ?>
            <li>
                <strong><?php echo htmlspecialchars($grupo['nome'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></strong>
                - <a class="botao-acao" href="listar_cartoes_grupo.php?id=<?php echo (int)$grupo['id']; ?>">👁️ Visualizar</a>
                <?php if ($isAdmin): ?>
                    | <a class="botao-acao" href="editar_grupo_cartao.php?id=<?php echo (int)$grupo['id']; ?>">✏️ Editar</a>
                    | <a class="botao-acao excluir" href="../includes/controle_excluir_grupo.php?id=<?php echo (int)$grupo['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este grupo?');">🗑️ Excluir</a>
                <?php endif; ?>
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
                <img src="../imagens/cartoes/<?php echo htmlspecialchars($cartao['imagem'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>"
                    alt="<?php echo htmlspecialchars(($cartao['texto_alternativo'] ?? '') ?: ($cartao['titulo'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>"><br>

                <strong><?php echo htmlspecialchars($cartao['titulo'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></strong><br>

                <div class="acoes-card" style="margin-top:8px; display:flex; gap:8px; flex-wrap:wrap;">
                    <button class="botao-acao" type="button"
                            onclick='falar(<?php echo json_encode($cartao["titulo"] ?? "", JSON_UNESCAPED_UNICODE); ?>)'
                            aria-label="Falar título do cartão <?php echo (int)$cartao['id']; ?>">
                    <span aria-hidden="true">🗣️</span> Falar
                    </button>

                    <?php if ($isAdmin): ?>
                    <a class="botao-acao"
                        href="editar_cartao.php?id=<?php echo (int)$cartao['id']; ?>"
                        aria-label="Editar cartão <?php echo (int)$cartao['id']; ?>">
                        <span aria-hidden="true">✏️</span> Editar
                    </a>

                    <a class="botao-acao excluir"
                        href="../includes/controle_excluir_cartao.php?id=<?php echo (int)$cartao['id']; ?>"
                        onclick="return confirm('Tem certeza que deseja excluir este cartão?');"
                        aria-label="Excluir cartão <?php echo (int)$cartao['id']; ?>">
                        <span aria-hidden="true">🗑️</span> Excluir
                    </a>
                    <?php endif; ?>
                </div>
                </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>Nenhum cartão cadastrado ainda.</p>
<?php endif; ?>

<script src="../assets/js/falar.js"></script>
<?php include '../includes/rodape.php'; ?>
