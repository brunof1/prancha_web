<?php include '../includes/cabecalho.php'; ?>
<?php require_once '../includes/controle_editar_cartao.php'; ?>

<h2>Editar Cartão</h2>

<?php if (!empty($mensagem)) echo "<p style='color:red;'>$mensagem</p>"; ?>

<form method="post">
    <label>Título:</label><br>
    <input type="text" name="titulo" value="<?php echo htmlspecialchars($cartao['titulo']); ?>" required><br><br>

    <label>Texto Alternativo:</label><br>
    <input type="text" name="texto_alternativo" value="<?php echo htmlspecialchars($cartao['texto_alternativo']); ?>"><br><br>

    <label>Caminho da Imagem:</label><br>
    <input type="text" name="imagem" value="<?php echo htmlspecialchars($cartao['imagem']); ?>"><br><br>

    <button type="submit">Salvar Alterações</button>
</form>

<?php include '../includes/rodape.php'; ?>
