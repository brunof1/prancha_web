<?php include '../includes/cabecalho.php'; ?>
<?php require_once '../includes/controle_nova_prancha.php'; ?>

<h2>Criar Nova Prancha</h2>

<?php if (!empty($mensagem_erro)) echo "<p style='color:red;'>$mensagem_erro</p>"; ?>
<?php if (!empty($mensagem_sucesso)) echo "<p style='color:green;'>$mensagem_sucesso</p>"; ?>

<form method="post">
    <label>Nome da Prancha:</label><br>
    <input type="text" name="nome_prancha" required><br><br>

    <label>Descrição:</label><br>
    <textarea name="descricao_prancha" rows="4" cols="30"></textarea><br><br>

    <button type="submit">Salvar Prancha</button>
</form>

<p><a href="gerenciar_pranchas.php">⬅️ Voltar para Gerenciar Pranchas</a></p>

<?php include '../includes/rodape.php'; ?>
