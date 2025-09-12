<?php

include '../includes/cabecalho.php';
require_once '../includes/controle_grupos_pranchas.php';
require_once '../includes/controle_pranchas.php';

$isAdmin = ($_SESSION['tipo_usuario'] === 'admin');

?>

<!-- Topo: botões de criar só para admin -->
<?php if ($isAdmin): ?>
<p>
  <a class="botao-acao" href="criar_grupo_prancha.php"><span aria-hidden="true">➕</span> Criar novo grupo de prancha</a>
  <a class="botao-acao" href="criar_prancha.php"><span aria-hidden="true">➕</span> Criar nova prancha</a>
</p>
<?php endif; ?>

<h2>Gerenciar Grupos de Pranchas</h2>

<?php if ($isAdmin && count($lista_grupos_pranchas) > 0): ?>
  <!-- … mesma lista com Editar/Excluir … -->
<?php elseif (!$isAdmin): ?>
  <p> </p> <!-- nada para usuário comum -->
<?php else: ?>
  <p>Nenhum grupo de prancha cadastrado ainda.</p>
<?php endif; ?>

<hr>

<h2>Gerenciar Pranchas</h2>

<?php if (count($lista_pranchas) > 0): ?>
  <ul>
    <?php foreach ($lista_pranchas as $prancha): ?>
      <?php
        $cartoes_ids = buscarCartoesDaPrancha($prancha['id']);
        $cartoes = buscarCartoesPorIds($cartoes_ids);
        $titulos_cartoes = array_column($cartoes, 'titulo');
      ?>
      <li style="margin-bottom:10px;">
        <strong><?php echo htmlspecialchars($prancha['nome']); ?> - </strong>
        <a class="botao-acao" href="ver_prancha.php?id=<?php echo $prancha['id']; ?>"><span aria-hidden="true">👁️</span> Visualizar</a>
        <?php if ($isAdmin): ?>
          <a class="botao-acao" href="editar_prancha.php?id=<?php echo $prancha['id']; ?>"><span aria-hidden="true">✏️</span> Editar</a>
          <a class="botao-acao excluir" href="../includes/controle_excluir_prancha.php?id=<?php echo $prancha['id']; ?>" onclick="return confirm('Tem certeza?');"><span aria-hidden="true">🗑️</span> Excluir</a>
        <?php endif; ?>
        <button type="button" class="botao-acao" onclick='falarListaDeCartoes(<?= json_encode($titulos_cartoes) ?>)'><span aria-hidden="true">🗣️</span> Falar</button>
      </li>
    <?php endforeach; ?>
  </ul>
<?php else: ?>
  <p>Nenhuma prancha cadastrada.</p>
<?php endif; ?>
<script src="../assets/js/falar.js"></script>
<?php include '../includes/rodape.php'; ?>