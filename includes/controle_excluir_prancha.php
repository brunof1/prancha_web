<?php
require_once 'modelo_pranchas.php';

if ($_SESSION['tipo_usuario'] !== 'admin') {
    http_response_code(403); echo "<p>Acesso restrito ao administrador.</p>";
    include '../includes/rodape.php'; exit;
}

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
