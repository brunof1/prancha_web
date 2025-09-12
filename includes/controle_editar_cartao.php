<?php
require_once 'modelo_cartoes.php';
require_once 'funcoes.php';

require_once __DIR__ . '/acl.php';
require_admin();


$mensagem = "";

if (isset($_GET['id'])) {
    $id_cartao = intval($_GET['id']);
    $cartao = buscarCartaoPorId($id_cartao);

    if (!$cartao) {
        die("Cartão não encontrado.");
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $titulo = limparEntrada($_POST['titulo']);
        $texto_alternativo = limparEntrada($_POST['texto_alternativo']);
        $imagem = limparEntrada($_POST['imagem']);

        if (atualizarCartao($id_cartao, $titulo, $texto_alternativo, $imagem)) {
            header('Location: gerenciar_cartoes.php');
            exit;
        } else {
            $mensagem = "Erro ao atualizar o cartão.";
        }
    }
} else {
    die("ID do cartão não informado.");
}
?>
