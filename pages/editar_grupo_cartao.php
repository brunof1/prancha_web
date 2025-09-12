<?php
// pages/editar_grupo_cartao.php

// 1) Carrega o cabeçalho primeiro (isso já faz session_start e protege por login)
include '../includes/cabecalho.php';

// 2) Garante que só admin acesse (inicia sessão se preciso e valida)
require_once '../includes/acl.php';
require_admin();

// 3) Demais dependências do fluxo
require_once '../includes/modelo_grupos.php';
require_once '../includes/funcoes.php';

// 4) Valida o ID do grupo
if (!isset($_GET['id'])) {
    echo "<p style='color:red;'>ID inválido.</p>";
    include '../includes/rodape.php';
    exit;
}

$id_grupo = intval($_GET['id']);
$grupo = buscarGrupoPorId($id_grupo);

if (!$grupo) {
    echo "<p style='color:red;'>Grupo não encontrado.</p>";
    include '../includes/rodape.php';
    exit;
}
?>

<h2>Editar Grupo de Cartões</h2>

<form action="../includes/controle_editar_grupo.php" method="post">
    <input type="hidden" name="id_grupo" value="<?php echo (int)$grupo['id']; ?>">
    <label>Nome do Grupo:</label><br>
    <input type="text" name="nome_grupo" value="<?php echo htmlspecialchars($grupo['nome']); ?>" required><br><br>
    <button type="submit">Salvar Alterações</button>
</form>

<?php include '../includes/rodape.php'; ?>
