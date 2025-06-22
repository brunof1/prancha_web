<?php include '../includes/cabecalho.php'; ?>
<?php require_once '../includes/controle_nova_prancha.php'; ?>

<h2>Criar Nova Prancha</h2>

<?php if (!empty($mensagem_erro)) echo "<p style='color:red;'>$mensagem_erro</p>"; ?>

<form method="post" enctype="multipart/form-data">

    <label>Nome da Prancha:</label><br>
    <input type="text" name="nome" required><br><br>

    <label>Descrição:</label><br>
    <textarea name="descricao" rows="4"></textarea><br><br>

    <label>Grupo da Prancha:</label><br>
    <select name="id_grupo" required>
        <option value="">Selecione um grupo...</option>
        <?php foreach ($grupos as $grupo): ?>
            <option value="<?php echo $grupo['id']; ?>">
                <?php echo htmlspecialchars($grupo['nome']); ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Selecione os Cartões:</label><br>
    <?php if (count($cartoes) > 0): ?>
        <div style="display: flex; flex-wrap: wrap;">
            <?php foreach ($cartoes as $cartao): ?>
                <div style="width: 150px; margin: 10px; text-align: center;">
                    <img src="../imagens/cartoes/<?php echo $cartao['imagem']; ?>" alt="<?php echo htmlspecialchars($cartao['texto_alternativo']); ?>" style="width: 100px; height: 100px; object-fit: cover;"><br>
                    <strong><?php echo htmlspecialchars($cartao['titulo']); ?></strong><br>
                    <label style="display: inline-flex; align-items: center; margin-top: 5px;">
                        <input type="checkbox" name="cartoes[]" value="<?php echo $cartao['id']; ?>">
                        <span style="margin-left: 5px;">Selecionar</span>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p style="color:red;">Nenhum cartão cadastrado. Crie cartões antes de criar pranchas.</p>
    <?php endif; ?>

    <br>
    <button type="submit">Salvar Prancha</button>
</form>

<?php include '../includes/rodape.php'; ?>
