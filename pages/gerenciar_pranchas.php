<?php
// pages/gerenciar_pranchas.php

include '../includes/cabecalho.php';
require_once '../includes/controle_grupos_pranchas.php';
require_once '../includes/controle_pranchas.php';

$isAdmin = ($_SESSION['tipo_usuario'] === 'admin');
?>

<?php if ($isAdmin): ?>
<p>
  <a class="botao-acao" href="criar_grupo_prancha.php"><span aria-hidden="true">➕</span> Criar novo grupo de prancha</a>
  <a class="botao-acao" href="criar_prancha.php"><span aria-hidden="true">➕</span> Criar nova prancha</a>
</p>
<?php endif; ?>

<?php if ($isAdmin): ?>
  <h2>Gerenciar Grupos de Pranchas</h2>

  <?php if (count($lista_grupos_pranchas) > 0): ?>
    <ul>
      <?php foreach ($lista_grupos_pranchas as $grupo): ?>
        <li>
          <strong><?php echo htmlspecialchars($grupo['nome'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?> - </strong>
          <a class="botao-acao" href="editar_grupo_prancha.php?id=<?php echo (int)$grupo['id']; ?>"><span aria-hidden="true">✏️</span> Editar</a>
          <a class="botao-acao excluir" href="../includes/controle_excluir_grupo_prancha.php?id=<?php echo (int)$grupo['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este grupo?');"><span aria-hidden="true">🗑️</span> Excluir</a>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <p>Nenhum grupo de prancha cadastrado ainda.</p>
  <?php endif; ?>

  <hr>
<?php endif; ?>

<h2><?php echo $isAdmin ? 'Gerenciar Pranchas' : 'Pranchas'; ?></h2>

<?php if (count($lista_pranchas) > 0): ?>
  <ul>
    <?php foreach ($lista_pranchas as $prancha): ?>
      <?php
        $cartoes_ids = buscarCartoesDaPrancha($prancha['id']);
        $cartoes = buscarCartoesPorIds($cartoes_ids);
        $titulos_cartoes = array_column($cartoes, 'titulo');
      ?>
      <li style="margin-bottom:10px;">
        <strong><?php echo htmlspecialchars($prancha['nome'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?> - </strong>
        <a class="botao-acao" href="ver_prancha.php?id=<?php echo (int)$prancha['id']; ?>"><span aria-hidden="true">👁️</span> Visualizar</a>
        <?php if ($isAdmin): ?>
          <a class="botao-acao" href="editar_prancha.php?id=<?php echo (int)$prancha['id']; ?>"><span aria-hidden="true">✏️</span> Editar</a>
          <a class="botao-acao excluir" href="../includes/controle_excluir_prancha.php?id=<?php echo (int)$prancha['id']; ?>" onclick="return confirm('Tem certeza?');"><span aria-hidden="true">🗑️</span> Excluir</a>
        <?php endif; ?>
        <button type="button" class="botao-acao" onclick='falarListaDeCartoes(<?php echo json_encode($titulos_cartoes, JSON_UNESCAPED_UNICODE); ?>)'><span aria-hidden="true">🗣️</span> Falar</button>
      </li>
    <?php endforeach; ?>
  </ul>
<?php else: ?>
  <p>Nenhuma prancha cadastrada.</p>
<?php endif; ?>

<script src="../assets/js/falar.js"></script>
<?php include '../includes/rodape.php'; ?>
