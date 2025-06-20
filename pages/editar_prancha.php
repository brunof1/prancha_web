<?php
include '../includes/cabecalho.php';
require_once '../includes/controle_editar_prancha.php';
?>

<h2>Editar Prancha</h2>

<?php if (!empty($mensagem_erro)) echo "<p style='color:red;'>$mensagem_erro</p>"; ?>
<?php if (!empty($mensagem_sucesso)) echo "<p style='color:green;'>$mensagem_sucesso</p>"; ?>

<form method="post">
    <input type="hidden" name="id_prancha" value="<?php echo htmlspecialchars($prancha['id']); ?>">

    <label>Nome da Prancha:</label><br>
    <input type="text" name="nome_prancha" value="<?php echo htmlspecialchars($prancha['nome']); ?>" required><br><br>

    <label>Descrição:</label><br>
    <textarea name="descricao_prancha" rows="4" cols="30"><?php echo htmlspecialchars($prancha['descricao']); ?></textarea><br><br>

    <button type="submit">Salvar Alterações</button>
</form>

<p><a href="gerenciar_pranchas.php">⬅️ Voltar para Gerenciar Pranchas</a></p>

<?php include '../includes/rodape.php'; ?>
