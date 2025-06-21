<?php include '../includes/cabecalho.php'; ?>
<?php require_once '../includes/modelo_cartoes.php'; ?>

<?php
$id_grupo = intval($_GET['id']);
$cartoes = listarCartoesPorGrupo($id_grupo);
?>

<h2>Cartões do Grupo</h2>

<?php if (count($cartoes) > 0): ?>
    <div style="display: flex; flex-wrap: wrap;">
        <?php foreach ($cartoes as $cartao): ?>
            <div style="width: 150px; margin: 10px; text-align: center;">
                <img src="../imagens/cartoes/<?php echo $cartao['imagem']; ?>" alt="<?php echo htmlspecialchars($cartao['texto_alternativo']); ?>" style="width: 100px; height: 100px; object-fit: cover;"><br>
                <strong><?php echo htmlspecialchars($cartao['titulo']); ?></strong><br>
                <button onclick="falar('<?php echo addslashes($cartao['titulo']); ?>')">🔊 Falar</button>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>Não há cartões neste grupo ainda.</p>
<?php endif; ?>

<script src="../assets/js/falar.js"></script>

<?php include '../includes/rodape.php'; ?>
