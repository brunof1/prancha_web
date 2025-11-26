<?php

/**
 * Prancha Web
 * Plataforma Web de Comunicação Alternativa e Aumentativa (CAA)
 *
 * Copyright (c) 2025 Bruno Silva da Silva
 *
 * Este arquivo faz parte do projeto Prancha Web.
 *
 * Licenciamento duplo:
 * - Apache License 2.0
 * - GNU General Public License v3.0 (GPLv3)
 *
 * Você pode redistribuir e/ou modificar este arquivo sob os termos de
 * qualquer uma das licenças, a seu critério, desde que cumpra integralmente
 * os respectivos requisitos.
 *
 * Você deve ter recebido uma cópia das licenças junto com este programa.
 * Caso contrário, veja:
 * - https://www.apache.org/licenses/LICENSE-2.0
 * - https://www.gnu.org/licenses/gpl-3.0.html
 */

require_once 'modelo_pranchas.php';
require_once 'funcoes.php';

require_once __DIR__ . '/acl.php';
require_admin();


$mensagem_erro = "";
$prancha = null;
$grupos = listarGruposPranchasPranchas();
$cartoes = listarCartoes();
$cartoes_selecionados = [];
$usuarios_nao_admin = listarUsuariosNaoAdmin();
$usuarios_vinculados = [];

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $prancha = buscarPranchaPorId($id);
    $cartoes_selecionados = buscarCartoesDaPrancha($id);
    $usuarios_vinculados = listarUsuariosDaPrancha($id);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $nome = limparEntrada($_POST['nome']);
    $descricao = limparEntrada($_POST['descricao']);
    $id_grupo = intval($_POST['id_grupo']);
    $cartoes_selecionados = isset($_POST['cartoes']) ? $_POST['cartoes'] : [];
    $ordem_cartoes = isset($_POST['ordem_cartoes']) && $_POST['ordem_cartoes'] !== ''
        ? array_map('intval', explode(',', $_POST['ordem_cartoes'])) : [];
    $usuariosSelecionados = isset($_POST['usuarios']) ? array_map('intval', $_POST['usuarios']) : [];

    if (empty($nome) || empty($id_grupo) || empty($cartoes_selecionados)) {
        $mensagem_erro = "Preencha todos os campos e selecione pelo menos um cartão.";
        $prancha = buscarPranchaPorId($id);
        $usuarios_vinculados = listarUsuariosDaPrancha($id);
    } else {
        if (atualizarPrancha($id, $nome, $descricao, $id_grupo, $ordem_cartoes ?: $cartoes_selecionados, $usuariosSelecionados)) {
            header('Location: ../pages/gerenciar_pranchas.php');
            exit;
        } else {
            $mensagem_erro = "Erro ao atualizar a prancha.";
            $prancha = buscarPranchaPorId($id);
            $usuarios_vinculados = listarUsuariosDaPrancha($id);
        }
    }
}
?>