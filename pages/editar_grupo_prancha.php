<?php
include '../includes/cabecalho.php';
require_once '../includes/modelo_grupos_pranchas.php';

if ($_SESSION['tipo_usuario'] !== 'admin') {
  http_response_code(403);
  echo "<p>Acesso restrito ao administrador.</p>";
  include '../includes/rodape.php';
  exit;
}

$mensagem = '';
$grupo = null;

if (!isset($_GET['id'])) {
  echo "<p>ID do grupo não informado.</p>";
  include '../includes/rodape.php';
  exit;
}

$id = (int)$_GET['id'];
$grupo = buscarGrupoPranchaPorId($id);
if (!$grupo) {
  echo "<p>Grupo não encontrado.</p>";
  include '../includes/rodape.php';
  exit;
}
?>

<h2>Editar Grupo de Pranchas</h2>

<?php if (!empty($_GET['ok'])): ?>
  <div class="alert alert--success" role="alert" aria-live="polite">Grupo atualizado.</div>
<?php endif; ?>

<form method="post" action="../includes/controle_editar_grupo_prancha.php">
  <input type="hidden" name="id" value="<?php echo (int)$grupo['id']; ?>">
  <div class="campo">
    <label for="nome">Nome do grupo</label><br>
    <input id="nome" name="nome" type="text" value="<?php echo htmlspecialchars($grupo['nome'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
  </div>
  <button type="submit" class="botao-acao">💾 Salvar</button>
  <a href="gerenciar_pranchas.php" class="botao-acao">↩️ Voltar</a>
</form>

<?php include '../includes/rodape.php'; ?>
