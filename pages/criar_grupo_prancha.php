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

<?php if (!empty($mensagem)) echo "<p style='color:red;'>$mensagem</p>"; ?>

<form method="post">
    <label>Nome do Grupo:</label><br>
    <input type="text" name="nome" required><br><br>
    <button type="submit">Salvar</button>
</form>

<?php include '../includes/rodape.php'; ?>
