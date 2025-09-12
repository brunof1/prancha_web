<?php
require_once 'modelo_grupos.php';

if ($_SESSION['tipo_usuario'] !== 'admin') {
    http_response_code(403); echo "<p>Acesso restrito ao administrador.</p>";
    include '../includes/rodape.php'; exit;
}

if (isset($_GET['id'])) {
    $id_grupo = intval($_GET['id']);

    if (excluirGrupo($id_grupo)) {
        header('Location: ../pages/gerenciar_cartoes.php?sucesso_excluir=1');
        exit;
    } else {
        header('Location: ../pages/gerenciar_cartoes.php?erro_excluir=1');
        exit;
    }
} else {
    echo "ID inválido.";
}
?>
