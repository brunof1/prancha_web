<?php
require_once 'modelo_cartoes.php';

if (isset($_GET['id'])) {
    $id_cartao = intval($_GET['id']);

    if (excluirCartao($id_cartao)) {
        header('Location: ../pages/gerenciar_cartoes.php?sucesso=1');
    } else {
        header('Location: ../pages/gerenciar_cartoes.php?erro=1');
    }
} else {
    echo "ID não informado.";
}
?>
