<?php
require_once 'modelo_pranchas.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if (excluirPrancha($id)) {
        header('Location: ../pages/gerenciar_pranchas.php?sucesso=1');
    } else {
        header('Location: ../pages/gerenciar_pranchas.php?erro=1');
    }
    exit;
}
?>
