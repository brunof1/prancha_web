<?php
include '../includes/cabecalho.php';
require_once '../includes/controle_editar_cartao.php';

if ($_SESSION['tipo_usuario'] !== 'admin') {
    http_response_code(403); echo "<p>Acesso restrito ao administrador.</p>";
    include '../includes/rodape.php'; exit;
}
?>

<h2>Editar Cartão</h2>

<?php if (!empty($mensagem)): ?>
  <div class="alert alert--danger" role="alert" aria-live="polite">
    <?php echo htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8'); ?>
  </div>
<?php endif; ?>

<?php if ($cartao): ?>
  <form method="post" enctype="multipart/form-data" aria-describedby="ajuda-imagem">
    <fieldset>
      <legend>Informações do cartão</legend>

      <div class="campo">
        <label for="titulo">Título</label><br>
        <input id="titulo" name="titulo" type="text"
               value="<?php echo htmlspecialchars($cartao['titulo'], ENT_QUOTES, 'UTF-8'); ?>"
               required aria-required="true">
      </div>

      <div class="campo">
        <label for="texto_alternativo">Texto alternativo</label><br>
        <input id="texto_alternativo" name="texto_alternativo" type="text"
               value="<?php echo htmlspecialchars($cartao['texto_alternativo'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
      </div>

      <div class="campo">
        <label for="id_grupo">Grupo</label><br>
        <select id="id_grupo" name="id_grupo" required aria-required="true">
          <option value="">Selecione um grupo...</option>
          <?php foreach ($lista_grupos as $g): ?>
            <option value="<?php echo (int)$g['id']; ?>"
              <?php echo ((int)$g['id'] === (int)$cartao['id_grupo']) ? 'selected' : ''; ?>>
              <?php echo htmlspecialchars($g['nome'], ENT_QUOTES, 'UTF-8'); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="campo">
        <label for="imagem">Imagem (opcional — deixa em branco para manter)</label><br>
        <input id="imagem" name="imagem" type="file" accept="image/*">
        <div id="ajuda-imagem" class="help">Formatos aceitos: jpg, jpeg, png, gif, webp. Máx. 4MB.</div>
      </div>

      <div class="campo" aria-label="Pré-visualização da imagem atual">
        <p class="help">Imagem atual:</p>
        <img src="../imagens/cartoes/<?php echo htmlspecialchars($cartao['imagem'], ENT_QUOTES, 'UTF-8'); ?>"
             alt="<?php echo htmlspecialchars($cartao['texto_alternativo'] ?? $cartao['titulo'], ENT_QUOTES, 'UTF-8'); ?>"
             style="max-width: 220px; height: auto; border-radius: 12px;">
      </div>
    </fieldset>

    <button type="submit" class="botao-acao">💾 Salvar alterações</button>
    <a class="botao-acao" href="gerenciar_cartoes.php">↩️ Voltar</a>
  </form>
<?php else: ?>
  <p>Cartão não encontrado.</p>
<?php endif; ?>

<?php include '../includes/rodape.php'; ?>
