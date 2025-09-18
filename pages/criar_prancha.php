<?php

include '../includes/cabecalho.php';
require_once '../includes/controle_nova_prancha.php';

?>

<h2>Criar Nova Prancha</h2>

<?php if (!empty($mensagem_erro)) echo "<p style='color:red;'>".htmlspecialchars($mensagem_erro, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')."</p>"; ?>

<form method="post">
    <label>Nome da Prancha:</label><br>
    <input type="text" name="nome" required><br><br>

    <label>Descrição:</label><br>
    <textarea name="descricao" rows="4"></textarea><br><br>

    <label>Grupo da Prancha:</label><br>
    <select name="id_grupo" required>
        <option value="">Selecione um grupo...</option>
        <?php foreach ($grupos as $grupo): ?>
            <option value="<?php echo (int)$grupo['id']; ?>">
                <?php echo htmlspecialchars($grupo['nome'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Selecione os Cartões:</label><br>
    <?php if (count($cartoes) > 0): ?>
        <div class="lista-cartoes">
            <?php foreach ($cartoes as $cartao): ?>
                <div class="cartao-item">
                    <img src="../imagens/cartoes/<?php echo htmlspecialchars($cartao['imagem'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($cartao['texto_alternativo'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>"><br>
                    <strong><?php echo htmlspecialchars($cartao['titulo'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></strong><br>
                    <label class="cartao-checkbox">
                        <input type="checkbox" name="cartoes[]" value="<?php echo (int)$cartao['id']; ?>">
                        Selecionar
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p style="color:red;">Nenhum cartão cadastrado. Crie cartões antes de criar pranchas.</p>
    <?php endif; ?>
    <fieldset style="margin-top:10px;">
    <legend><strong>Compartilhar com usuários (não administradores)</strong></legend>
    <?php if (!empty($usuarios_nao_admin)): ?>
      <?php foreach ($usuarios_nao_admin as $u): ?>
        <label style="display:inline-flex;align-items:center;margin:4px 12px 4px 0;">
          <input type="checkbox" name="usuarios[]" value="<?php echo (int)$u['id']; ?>" style="margin-right:6px;">
          <?php echo htmlspecialchars($u['nome'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>
          <small style="margin-left:6px;opacity:.7;">(<?php echo htmlspecialchars($u['email'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>)</small>
        </label>
      <?php endforeach; ?>
    <?php else: ?>
      <p>Nenhum usuário não-admin cadastrado.</p>
    <?php endif; ?>
  </fieldset>

    <br>
    <input type="hidden" name="ordem_cartoes" id="ordem_cartoes">
    <button type="submit">Salvar Prancha</button>
</form>
<script src="../assets/js/ordem_cartoes.js"></script>
<?php include '../includes/rodape.php'; ?>
