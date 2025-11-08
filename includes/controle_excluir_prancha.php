<?php
require_once 'modelo_pranchas.php';

require_once __DIR__ . '/acl.php';
require_admin();


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
