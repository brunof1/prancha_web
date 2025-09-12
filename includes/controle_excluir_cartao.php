<?php
require_once 'modelo_cartoes.php';

if ($_SESSION['tipo_usuario'] !== 'admin') {
    http_response_code(403); echo "<p>Acesso restrito ao administrador.</p>";
    include '../includes/rodape.php'; exit;
}

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
