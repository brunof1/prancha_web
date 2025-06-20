<?php
require_once 'modelo_grupos.php';

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
