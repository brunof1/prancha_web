<?php include '../includes/cabecalho.php'; ?>
<?php require_once '../includes/modelo_cartoes.php'; ?>

<?php
$id_grupo = intval($_GET['id']);
$cartoes = listarCartoesPorGrupo($id_grupo);
?>

<h2>Cartões do Grupo</h2>

<?php if (count($cartoes) > 0): ?>
  <div class="lista-cartoes">
    <?php foreach ($cartoes as $cartao): ?>
      <div class="cartao-item">
        <img src="../imagens/cartoes/<?php echo htmlspecialchars($cartao['imagem']); ?>"
             alt="<?php echo htmlspecialchars($cartao['texto_alternativo']); ?>"><br>
        <strong><?php echo htmlspecialchars($cartao['titulo']); ?></strong><br>
        <button class="botao-acao" type="button" onclick="falar('<?php echo addslashes($cartao['titulo']); ?>')">
          <span aria-hidden="true">🗣️</span> Falar
        </button>
      </div>
    <?php endforeach; ?>
  </div>
<?php else: ?>
  <p>Não há cartões neste grupo ainda.</p>
<?php endif; ?>


<script src="../assets/js/falar.js"></script>

<?php include '../includes/rodape.php'; ?>
