
<?php
require_once '../includes/controle_novo_cartao.php';
include '../includes/cabecalho.php';
?>

<h2>Criar Novo Cartão</h2>

<?php if (!empty($mensagem)) echo "<p style='color:red;'>$mensagem</p>"; ?>

<form method="post" enctype="multipart/form-data">
    <label>Título:</label><br>
    <input type="text" name="titulo" required><br><br>

    <label>Texto Alternativo:</label><br>
    <input type="text" name="texto_alternativo"><br><br>

    <label>Grupo:</label><br>
    <select name="id_grupo" required>
        <?php foreach ($lista_grupos as $grupo): ?>
            <option value="<?php echo $grupo['id']; ?>"><?php echo htmlspecialchars($grupo['nome']); ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Imagem:</label><br>
    <input type="file" name="imagem" accept="image/*" required><br><br>

    <button type="submit">Salvar Cartão</button>
</form>

<?php include '../includes/rodape.php'; ?>
