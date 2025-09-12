<?php

include '../includes/cabecalho.php';

if ($_SESSION['tipo_usuario'] !== 'admin') {
    http_response_code(403); echo "<p>Acesso restrito ao administrador.</p>";
    include '../includes/rodape.php'; exit;
}

?>

<h2>Criar Novo Grupo de Cartões</h2>

<form action="../includes/controle_criar_grupo.php" method="post">
    <label>Nome do Grupo:</label><br>
    <input type="text" name="nome_grupo" required><br><br>
    <button type="submit">Salvar Grupo</button>
</form>

<?php include '../includes/rodape.php'; ?>
