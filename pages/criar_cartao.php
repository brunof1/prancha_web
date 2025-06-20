<?php include '../includes/cabecalho.php'; ?>
<?php require_once '../includes/modelo_cartoes.php'; ?>
<?php require_once '../includes/controle_criar_cartao.php'; ?>

<h2>Criar Cartão</h2>

<?php if (!empty($mensagem_erro)) echo "<p style='color:red;'>$mensagem_erro</p>"; ?>

<form method="post" enctype="multipart/form-data">
    <label>Título do Cartão:</label><br>
    <input type="text" name="titulo" required><br><br>

    <label>Texto Alternativo:</label><br>
    <input type="text" name="texto_alt" required><br><br>

    <label>Imagem:</label><br>
    <input type="file" name="imagem" accept="image/*" required><br><br>

    <label>Grupo:</label><br>
    <select name="id_grupo" required>
        <option value="">Selecione o grupo</option>
        <?php foreach (listarGrupos() as $grupo): ?>
            <option value="<?php echo $grupo['id']; ?>"><?php echo htmlspecialchars($grupo['nome']); ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <button type="submit">Salvar Cartão</button>
</form>

<?php include '../includes/rodape.php'; ?>
