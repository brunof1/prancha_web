<?php
require_once 'modelo_pranchas.php';

if (isset($_GET['id'])) {
    $id_prancha = intval($_GET['id']);

    if (excluirPrancha($id_prancha)) {
        header('Location: ../pages/gerenciar_pranchas.php?sucesso=1');
        exit;
    } else {
        header('Location: ../pages/gerenciar_pranchas.php?erro=1');
        exit;
    }
} else {
    header('Location: ../pages/gerenciar_pranchas.php');
    exit;
}
?>
