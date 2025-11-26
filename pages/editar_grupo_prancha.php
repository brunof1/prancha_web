<?php

/**
 * Prancha Web
 * Plataforma Web de Comunicação Alternativa e Aumentativa (CAA)
 *
 * Copyright (c) 2025 Bruno Silva da Silva
 *
 * Este arquivo faz parte do projeto Prancha Web.
 *
 * Licenciamento duplo:
 * - Apache License 2.0
 * - GNU General Public License v3.0 (GPLv3)
 *
 * Você pode redistribuir e/ou modificar este arquivo sob os termos de
 * qualquer uma das licenças, a seu critério, desde que cumpra integralmente
 * os respectivos requisitos.
 *
 * Você deve ter recebido uma cópia das licenças junto com este programa.
 * Caso contrário, veja:
 * - https://www.apache.org/licenses/LICENSE-2.0
 * - https://www.gnu.org/licenses/gpl-3.0.html
 */

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
  <fieldset>
    <legend>Informações do grupo de prancha(s)</legend>
    <input type="hidden" name="id" value="<?php echo (int)$grupo['id']; ?>">
    <div class="campo">
      <label for="nome">Nome do grupo</label><br>
      <input id="nome" name="nome" type="text" value="<?php echo htmlspecialchars($grupo['nome'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
    </div>
  </fieldset>
  <br>
  <button type="submit" class="botao-acao">💾 Salvar Alterações</button>
  <a class="botao-acao" href="gerenciar_pranchas.php">↩️ Voltar</a>
</form>

<?php include '../includes/rodape.php'; ?>
