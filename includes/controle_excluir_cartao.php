<?php
require_once 'modelo_cartoes.php';

require_once __DIR__ . '/acl.php';
require_admin();


if (isset($_GET['id'])) {
    $id_cartao = intval($_GET['id']);
    if (excluirCartao($id_cartao)) {
        header("Location: ../pages/gerenciar_cartoes.php?sucesso_excluir=1");
    } else {
        header("Location: ../pages/gerenciar_cartoes.php?erro_excluir=1");
    }
} else {
    header("Location: ../pages/gerenciar_cartoes.php");
}
exit;
?>
