<?php
require_once '../includes/modelo_grupos.php';
require_once '../includes/funcoes.php';

if ($_SESSION['tipo_usuario'] !== 'admin') {
    http_response_code(403); echo "<p>Acesso restrito ao administrador.</p>";
    include '../includes/rodape.php'; exit;
}

if (!isset($_GET['id'])) {
    die("ID inválido.");
}

$id_grupo = intval($_GET['id']);
$grupo = buscarGrupoPorId($id_grupo);

if (!$grupo) {
    die("Grupo não encontrado.");
}

include '../includes/cabecalho.php';
?>

<h2>Editar Grupo de Cartões</h2>

<form action="../includes/controle_editar_grupo.php" method="post">
    <input type="hidden" name="id_grupo" value="<?php echo $grupo['id']; ?>">
    <label>Nome do Grupo:</label><br>
    <input type="text" name="nome_grupo" value="<?php echo htmlspecialchars($grupo['nome']); ?>" required><br><br>
    <button type="submit">Salvar Alterações</button>
</form>

<?php include '../includes/rodape.php'; ?>
