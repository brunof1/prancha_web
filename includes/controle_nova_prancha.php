<?php
require_once 'modelo_pranchas.php';
require_once 'funcoes.php';

require_once __DIR__ . '/acl.php';
require_admin();


$mensagem_erro = "";
$grupos  = listarGruposPranchasPranchas();
$cartoes = listarCartoes();
$usuarios_nao_admin = listarUsuariosNaoAdmin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = limparEntrada($_POST['nome']);
    $descricao = limparEntrada($_POST['descricao']);
    $id_grupo = intval($_POST['id_grupo']);
    $cartoes_selecionados = isset($_POST['cartoes']) ? $_POST['cartoes'] : [];
    $ordem_cartoes = isset($_POST['ordem_cartoes']) && $_POST['ordem_cartoes'] !== ''
        ? array_map('intval', explode(',', $_POST['ordem_cartoes'])) : [];
    $usuariosSelecionados = isset($_POST['usuarios']) ? array_map('intval', $_POST['usuarios']) : [];

    if (empty($nome) || empty($id_grupo) || empty($cartoes_selecionados)) {
        $mensagem_erro = "Preencha todos os campos e selecione pelo menos um cartão.";
    } else {
        if (salvarPrancha($nome, $descricao, $id_grupo, $ordem_cartoes ?: $cartoes_selecionados, $usuariosSelecionados)) {
            header('Location: ../pages/gerenciar_pranchas.php');
            exit;
        } else {
            $mensagem_erro = "Erro ao salvar a prancha.";
        }
    }
}
?>