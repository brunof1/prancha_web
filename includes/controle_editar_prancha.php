<?php
require_once 'modelo_pranchas.php';
require_once 'funcoes.php';

$mensagem_erro = "";
$prancha = null;

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $prancha = buscarPranchaPorId($id);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $nome = limparEntrada($_POST['nome']);
    $descricao = limparEntrada($_POST['descricao']);

    if (empty($nome)) {
        $mensagem_erro = "O nome da prancha é obrigatório.";
    } else {
        if (atualizarPrancha($id, $nome, $descricao)) {
            header('Location: ../pages/gerenciar_pranchas.php');
            exit;
        } else {
            $mensagem_erro = "Erro ao atualizar a prancha.";
        }
    }
}
?>
