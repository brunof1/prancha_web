<?php
// pages/gerenciar_pranchas.php
include '../includes/cabecalho.php';
require_once '../includes/controle_pranchas.php';

// Todos podem visualizar e falar;
// Admin também pode criar/editar/excluir.
$isAdmin = (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin');
?>
<h2 id="titulo-lista">Pranchas</h2>

<?php if ($isAdmin): ?>
  <p style="display:flex; gap:8px; flex-wrap:wrap;">
    <a class="botao-acao" href="criar_prancha.php">➕ Nova prancha</a>
    <a class="botao-acao" href="criar_grupo_prancha.php">🗂️ Novo grupo de pranchas</a>
  </p>
<?php endif; ?>

<?php if (empty($lista_pranchas)): ?>
  <div class="alert" role="status" aria-live="polite">
    Nenhuma prancha disponível para você no momento.
  </div>
<?php else: ?>
  <table class="tabela" role="table" aria-describedby="titulo-lista">
    <colgroup>
      <col class="id" style="width:72px;">
      <col class="nome">
      <col class="grupo" style="width:28%;">
      <col class="acoes" style="width:32%;">
    </colgroup>
    <thead>
      <tr>
        <th scope="col">ID</th>
        <th scope="col">Nome</th>
        <th scope="col">Grupo</th>
        <th scope="col">Ações</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($lista_pranchas as $p): ?>
      <tr>
        <td style="text-align:center;"><?php echo (int)$p['id']; ?></td>
        <td><?php echo htmlspecialchars($p['nome'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
        <td><?php echo htmlspecialchars($p['grupo'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
        <td class="celula-acao">
          <!-- Liberados para todos -->
          <a
            class="botao-acao"
            href="ver_prancha.php?id=<?php echo (int)$p['id']; ?>"
            title="Visualizar prancha">
            👁️ Visualizar
          </a>

          <button
            type="button"
            class="botao-acao"
            title="Falar nome da prancha"
            onclick="falar('Prancha: <?php echo htmlspecialchars($p['nome'] ?? '', ENT_QUOTES, 'UTF-8'); ?>');">
            🗣️ Falar
          </button>

          <?php if ($isAdmin): ?>
            <!-- Somente admin -->
            <a
              class="botao-acao"
              href="editar_prancha.php?id=<?php echo (int)$p['id']; ?>"
              title="Editar prancha">
              ✏️ Editar
            </a>

            <a
              class="botao-acao excluir"
              href="../includes/controle_excluir_prancha.php?id=<?php echo (int)$p['id']; ?>"
              title="Excluir prancha"
              onclick="return confirmarExclusaoPrancha(<?php echo (int)$p['id']; ?>, '<?php echo htmlspecialchars($p['nome'] ?? '', ENT_QUOTES, 'UTF-8'); ?>');">
              🗑️ Excluir
            </a>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>

<!-- Falar (TTS) -->
<script src="../assets/js/falar.js"></script>
<script>
// Confirmação de exclusão (apenas admins verão o botão)
function confirmarExclusaoPrancha(id, nome){
  return confirm(
    'Tem certeza que deseja excluir a prancha #' + id +
    (nome ? ' "' + nome + '"' : '') +
    '?\nEsta ação é irreversível.'
  );
}
</script>

<?php include '../includes/rodape.php'; ?>
