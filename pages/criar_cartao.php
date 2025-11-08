<?php
// pages/criar_cartao.php
include '../includes/cabecalho.php';
require_once '../includes/modelo_cartoes.php';
require_once '../includes/modelo_grupos.php';
require_once '../includes/controle_criar_cartao.php';

if ($_SESSION['tipo_usuario'] !== 'admin') {
    http_response_code(403); echo "<p>Acesso restrito ao administrador.</p>";
    include '../includes/rodape.php'; exit;
}
?>
<h2>Criar Cartão</h2>

<?php if (!empty($mensagem_erro)): ?>
  <div class="alert alert--danger" role="alert" aria-live="polite">
    <?php echo htmlspecialchars($mensagem_erro, ENT_QUOTES, 'UTF-8'); ?>
  </div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" aria-describedby="ajuda-imagem">
  <button type="submit" class="botao-acao">💾 Salvar Cartão</button>
  <a class="botao-acao" href="gerenciar_cartoes.php">↩️ Voltar</a>
  <br><br>
  <fieldset>
    <legend>Informações do cartão</legend>

    <div class="campo">
      <label for="titulo">Título</label><br>
      <input id="titulo" name="titulo" type="text" required aria-required="true">
    </div>

    <div class="campo">
      <label for="texto_alt">Texto alternativo</label><br>
      <input id="texto_alt" name="texto_alt" type="text" required aria-required="true">
    </div>

    <div class="campo">
      <label for="id_grupo">Grupo</label><br>
      <select id="id_grupo" name="id_grupo" required aria-required="true">
        <option value="">Selecione um grupo...</option>
        <?php foreach (listarGrupos() as $grupo): ?>
          <option value="<?php echo (int)$grupo['id']; ?>">
            <?php echo htmlspecialchars($grupo['nome'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="campo">
      <label for="imagem">Enviar imagem (opcional)</label><br>
      <input id="imagem" name="imagem" type="file" accept="image/*">
      <div id="ajuda-imagem" class="help">Formatos aceitos: svg, jpg, jpeg, png, gif, webp. Máx. 4MB.</div>
    </div>

    <fieldset style="margin-top:14px;">
      <legend>Ou buscar na ARASAAC</legend>
      <div id="arasaac-box">
        <label for="arasaac-q">Buscar (PT-BR):</label><br>
        <div style="display:flex;gap:8px;align-items:center;">
          <input id="arasaac-q" type="text" placeholder="Ex.: banho, comida, escola..." style="flex:1;">
          <select id="arasaac-lang" aria-label="Idioma">
            <option value="pt" selected>pt</option>
            <option value="es">es</option>
            <option value="en">en</option>
          </select>
          <button type="button" id="arasaac-buscar" class="botao-acao">🔎 Buscar</button>
        </div>
        <div id="arasaac-status" class="help" style="margin-top:6px;"></div>
        <ul id="arasaac-resultados" style="list-style:none;padding-left:0;margin-top:8px;display:grid;gap:8px;"></ul>
        <input type="hidden" name="imagem_remota" id="imagem_remota" value="">
      </div>
    </fieldset>
  </fieldset>
  <br>
  <button type="submit" class="botao-acao">💾 Salvar Cartão</button>
  <a class="botao-acao" href="gerenciar_cartoes.php">↩️ Voltar</a>
</form>

<script src="../assets/js/arasaac.js"></script>
<?php include '../includes/rodape.php'; ?>
