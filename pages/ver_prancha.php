<?php include '../includes/cabecalho.php'; ?>
<?php require_once '../includes/modelo_pranchas.php'; ?>

<?php
$id_prancha = intval($_GET['id']);
$prancha = buscarPranchaPorId($id_prancha);
$cartoes_ids = buscarCartoesDaPrancha($id_prancha);
$cartoes = buscarCartoesPorIds($cartoes_ids);
?>

<h2>Prancha: <?php echo htmlspecialchars($prancha['nome']); ?></h2>
<p><?php echo htmlspecialchars($prancha['descricao']); ?></p>

<script>
    const listaDeCartoes = <?php echo json_encode(array_column($cartoes, 'titulo')); ?>;
</script>

    <button class="botao-falar-tudo" onclick="falarListaDeCartoes(listaDeCartoes)">🔊 Falar Tudo</button>

<?php if (count($cartoes) > 0): ?>
    <div class="lista-cartoes">
        <?php foreach ($cartoes as $cartao): ?>
            <div class="cartao-item">
                <img src="../imagens/cartoes/<?php echo htmlspecialchars($cartao['imagem']); ?>" alt="<?php echo htmlspecialchars($cartao['texto_alternativo']); ?>"><br>
                <strong><?php echo htmlspecialchars($cartao['titulo']); ?></strong><br>
                <button onclick="falar('<?php echo addslashes($cartao['titulo']); ?>')">🔊 Falar</button>
            </div>
        <?php endforeach; ?>
    </div>

    <br>

<?php else: ?>
    <p>Nenhum cartão nesta prancha.</p>
<?php endif; ?>
<script src="../assets/js/falar.js"></script>
<?php include '../includes/rodape.php'; ?>
