<?php
require_once 'modelo_grupos.php';
require_once 'funcoes.php';
require_once __DIR__ . '/acl.php';
require_admin();

if ($_SESSION['tipo_usuario'] !== 'admin') {
    http_response_code(403); echo "<p>Acesso restrito ao administrador.</p>";
    include '../includes/rodape.php'; exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_grupo = limparEntrada($_POST['nome_grupo']);

    if (criarGrupo($nome_grupo)) {
        header('Location: ../pages/gerenciar_cartoes.php?gsucesso=1');
        exit;
    } else {
        header('Location: ../pages/gerenciar_cartoes.php?erro=1');
        exit;
    }
} else {
    echo "Requisição inválida.";
}
?>
