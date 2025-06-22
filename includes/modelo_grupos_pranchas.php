<?php
require_once '../config/config.php';

// Lista todos os grupos de pranchas
function listarGruposPranchas() {
    $conexao = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
    $grupos = [];

    $sql = "SELECT id, nome FROM grupos_pranchas";
    $comando = $conexao->prepare($sql);
    $comando->execute();
    $comando->bind_result($id, $nome);

    while ($comando->fetch()) {
        $grupos[] = ['id' => $id, 'nome' => $nome];
    }

    $comando->close();
    $conexao->close();

    return $grupos;
}

// Cria um novo grupo de prancha
function criarGrupoPrancha($nome) {
    $conexao = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
    if ($conexao->connect_error) return false;

    $sql = "INSERT INTO grupos_pranchas (nome) VALUES (?)";
    $comando = $conexao->prepare($sql);
    $comando->bind_param("s", $nome);
    $resultado = $comando->execute();

    $comando->close();
    $conexao->close();
    return $resultado;
}

// Busca um grupo pelo ID
function buscarGrupoPranchaPorId($id) {
    $conexao = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
    $sql = "SELECT id, nome FROM grupos_pranchas WHERE id = ?";
    $comando = $conexao->prepare($sql);
    $comando->bind_param("i", $id);
    $comando->execute();
    $comando->bind_result($id_result, $nome_result);

    $grupo = null;
    if ($comando->fetch()) {
        $grupo = ['id' => $id_result, 'nome' => $nome_result];
    }

    $comando->close();
    $conexao->close();

    return $grupo;
}

// Atualiza o nome de um grupo
function atualizarGrupoPrancha($id, $nome) {
    $conexao = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
    $sql = "UPDATE grupos_pranchas SET nome = ? WHERE id = ?";
    $comando = $conexao->prepare($sql);
    $comando->bind_param("si", $nome, $id);
    $resultado = $comando->execute();

    $comando->close();
    $conexao->close();

    return $resultado;
}

// Exclui um grupo de prancha
function excluirGrupoPrancha($id) {
    $conexao = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
    $sql = "DELETE FROM grupos_pranchas WHERE id = ?";
    $comando = $conexao->prepare($sql);
    $comando->bind_param("i", $id);
    $resultado = $comando->execute();

    $comando->close();
    $conexao->close();

    return $resultado;
}
?>
