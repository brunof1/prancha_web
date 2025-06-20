<?php
require_once 'funcoes.php';
require_once 'modelo_pranchas.php';

$mensagem_erro = "";
$mensagem_sucesso = "";

// Se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_prancha = limparEntrada($_POST['nome_prancha']);
    $descricao_prancha = limparEntrada($_POST['descricao_prancha']);

    if (empty($nome_prancha)) {
        $mensagem_erro = "O nome da prancha é obrigatório.";
    } else {
        $resultado = criarPrancha($nome_prancha, $descricao_prancha);

        if ($resultado) {
            // Redireciona de volta para gerenciar_pranchas com uma mensagem de sucesso
            header('Location: ../pages/gerenciar_pranchas.php?sucesso=1');
            exit;
        } else {
            $mensagem_erro = "Erro ao criar a prancha. Tente novamente.";
        }
    }
}
?>
