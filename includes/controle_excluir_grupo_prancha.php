<?php
require_once 'modelo_grupos_pranchas.php';

if ($_SESSION['tipo_usuario'] !== 'admin') {
    http_response_code(403); echo "<p>Acesso restrito ao administrador.</p>";
    include '../includes/rodape.php'; exit;
}

$id_grupo = intval($_GET['id']);

if (excluirGrupoPrancha($id_grupo)) {
    header('Location: ../pages/gerenciar_pranchas.php');
} else {
    echo "Erro ao excluir o grupo.";
}
?>
