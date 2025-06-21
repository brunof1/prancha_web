<?php
require_once 'modelo_grupos.php';
require_once 'funcoes.php';

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
