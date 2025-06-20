<?php
require_once 'funcoes.php';
require_once 'modelo_pranchas.php';

$mensagem_erro = "";
$mensagem_sucesso = "";
$prancha = ['id' => '', 'nome' => '', 'descricao' => ''];

// Verificar se tem um ID na URL
if (isset($_GET['id'])) {
    $id_prancha = intval($_GET['id']);
    $prancha = buscarPranchaPorId($id_prancha);

    if (!$prancha) {
        $mensagem_erro = "Prancha não encontrada.";
    }
}

// Se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_prancha = intval($_POST['id_prancha']);
    $nome_prancha = limparEntrada($_POST['nome_prancha']);
    $descricao_prancha = limparEntrada($_POST['descricao_prancha']);

    if (empty($nome_prancha)) {
        $mensagem_erro = "O nome da prancha é obrigatório.";
    } else {
        if (atualizarPrancha($id_prancha, $nome_prancha, $descricao_prancha)) {
            $mensagem_sucesso = "Prancha atualizada com sucesso!";
            $prancha = buscarPranchaPorId($id_prancha);
        } else {
            $mensagem_erro = "Erro ao atualizar a prancha.";
        }
    }
}
?>
