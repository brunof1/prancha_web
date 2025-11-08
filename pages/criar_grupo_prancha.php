<?php
include '../includes/cabecalho.php';
require_once '../includes/modelo_grupos_pranchas.php';

if ($_SESSION['tipo_usuario'] !== 'admin') {
    http_response_code(403); echo "<p>Acesso restrito ao administrador.</p>";
    include '../includes/rodape.php'; exit;
}
?>

<h2>Criar Novo Grupo de Pranchas</h2>

<?php
$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_grupo = trim($_POST['nome']);
    if (!empty($nome_grupo)) {
        if (criarGrupoPrancha($nome_grupo)) {
            header('Location: gerenciar_pranchas.php');
            exit;
        } else {
            $mensagem = "Erro ao criar o grupo.";
        }
    } else {
        $mensagem = "Por favor, informe um nome.";
    }
}
?>

<?php if (!empty($mensagem)) echo "<p style='color:red;'>" . htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') . "</p>"; ?>

<form method="post">
    <fieldset>
        <legend>Informações do novo grupo de prancha(s)</legend>
        <label>Nome do Grupo:</label><br>
        <input type="text" name="nome" required><br><br>
    </fieldset>
    <br>
    <!--
    <button type="submit">Salvar</button>
    -->
    <button type="submit" class="botao-acao">💾 Salvar Novo Grupo de Prancha(s)</button>
    <a class="botao-acao" href="gerenciar_pranchas.php">↩️ Voltar</a>
</form>

<?php include '../includes/rodape.php'; ?>
