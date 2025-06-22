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

        <button type="submit">Salvar Alterações</button>
    </form>
<?php else: ?>
    <p>Prancha não encontrada.</p>
<?php endif; ?>

<?php include '../includes/rodape.php'; ?>
