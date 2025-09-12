<?php include '../includes/cabecalho.php'; ?>
<?php require_once '../includes/controle_editar_prancha.php'; ?>

<h2>Editar Prancha</h2>

<?php if ($prancha): ?>
    <?php if (!empty($mensagem_erro)) echo "<p style='color:red;'>$mensagem_erro</p>"; ?>

    <form method="post">
        <input type="hidden" name="id" value="<?php echo $prancha['id']; ?>">

        <label>Nome:</label><br>
        <input type="text" name="nome" value="<?php echo htmlspecialchars($prancha['nome']); ?>" required><br><br>

        <label>Descrição:</label><br>
        <textarea name="descricao" rows="4"><?php echo htmlspecialchars($prancha['descricao']); ?></textarea><br><br>

        <label>Grupo da Prancha:</label><br>
        <select name="id_grupo" required>
            <?php foreach ($grupos as $grupo): ?>
                <option value="<?php echo $grupo['id']; ?>" <?php if ($grupo['id'] == $prancha['id_grupo']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($grupo['nome']); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Selecione os Cartões:</label><br>
        <?php if (count($cartoes) > 0): ?>
            <div class="lista-cartoes">
                <?php foreach ($cartoes as $cartao): ?>
                    <div class="cartao-item">
                        <img src="../imagens/cartoes/<?php echo htmlspecialchars($cartao['imagem']); ?>" alt="<?php echo htmlspecialchars($cartao['texto_alternativo']); ?>"><br>
                        <strong><?php echo htmlspecialchars($cartao['titulo']); ?></strong><br>
                        <label class="cartao-checkbox">
                            <input type="checkbox" name="cartoes[]" value="<?php echo $cartao['id']; ?>"
                                <?php if (in_array($cartao['id'], $cartoes_selecionados)) echo 'checked'; ?>>
                            Selecionar
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p style="color:red;">Nenhum cartão cadastrado.</p>
        <?php endif; ?>
        <fieldset style="margin-top:10px;">
  <legend><strong>Compartilhar com usuários (não administradores)</strong></legend>
  <?php if (!empty($usuarios_nao_admin)): ?>
    <?php foreach ($usuarios_nao_admin as $u): ?>
      <label style="display:inline-flex;align-items:center;margin:4px 12px 4px 0;">
        <input type="checkbox" name="usuarios[]" value="<?php echo (int)$u['id']; ?>" <?php echo in_array($u['id'], $usuarios_vinculados) ? 'checked' : ''; ?> style="margin-right:6px;">
        <?php echo htmlspecialchars($u['nome']); ?> <small style="margin-left:6px;opacity:.7;">(<?php echo htmlspecialchars($u['email']); ?>)</small>
      </label>
    <?php endforeach; ?>
  <?php else: ?>
    <p>Nenhum usuário não-admin cadastrado.</p>
  <?php endif; ?>
</fieldset>

        <br>
        <input type="hidden" name="ordem_cartoes" id="ordem_cartoes">
        <button type="submit">Salvar Alterações</button>
    </form>
<?php else: ?>
    <p>Prancha não encontrada.</p>
<?php endif; ?>
<script src="../assets/js/ordem_cartoes.js"></script>
<?php include '../includes/rodape.php'; ?>
