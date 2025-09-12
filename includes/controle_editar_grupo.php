<?php
require_once 'modelo_grupos.php';
require_once 'funcoes.php';

if ($_SESSION['tipo_usuario'] !== 'admin') {
    http_response_code(403); echo "<p>Acesso restrito ao administrador.</p>";
    include '../includes/rodape.php'; exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_grupo = intval($_POST['id_grupo']);
    $nome_grupo = limparEntrada($_POST['nome_grupo']);

    if (atualizarGrupo($id_grupo, $nome_grupo)) {
        header('Location: ../pages/gerenciar_cartoes.php?sucesso_editar=1');
        exit;
    } else {
        header('Location: ../pages/gerenciar_cartoes.php?erro_editar=1');
        exit;
    }
} else {
    echo "Requisição inválida.";
}
?>
