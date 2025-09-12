<?php
include '../includes/cabecalho.php';
require_once '../includes/modelo_grupos_pranchas.php';

if ($_SESSION['tipo_usuario'] !== 'admin') {
    http_response_code(403); echo "<p>Acesso restrito ao administrador.</p>";
    include '../includes/rodape.php'; exit;
}
?>

<?php
$id_grupo = intval($_GET['id']);
$grupo = buscarGrupoPranchaPorId($id_grupo);
$mensagem = "";

if (!$grupo) {
    echo "<p style='color:red;'>Grupo não encontrado.</p>";
    include '../includes/rodape.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $novo_nome = trim($_POST['nome']);
    if (!empty($novo_nome)) {
        if (atualizarGrupoPrancha($id_grupo, $novo_nome)) {
            header('Location: gerenciar_pranchas.php');
            exit;
        } else {
            $mensagem = "Erro ao atualizar o grupo.";
        }
    } else {
        $mensagem = "O nome não pode estar vazio.";
    }
}
?>

<h2>Editar Grupo de Pranchas</h2>

<?php if (!empty($mensagem)) echo "<p style='color:red;'>$mensagem</p>"; ?>

<form method="post">
    <label>Nome do Grupo:</label><br>
    <input type="text" name="nome" value="<?php echo htmlspecialchars($grupo['nome']); ?>" required><br><br>
    <button type="submit">Salvar Alterações</button>
</form>

<?php include '../includes/rodape.php'; ?>
