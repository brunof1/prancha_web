<?php
require_once 'modelo_grupos.php';
require_once 'funcoes.php';

require_once __DIR__ . '/acl.php';
require_admin();


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
