<?php
require_once '../config/config.php';

function listarPranchas() {
    $conexao = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);

    if ($conexao->connect_error) {
        die("Erro de conexão: " . $conexao->connect_error);
    }

    $pranchas = [];
    $resultado = $conexao->query("SELECT id, nome FROM pranchas");

    if ($resultado && $resultado->num_rows > 0) {
        while ($linha = $resultado->fetch_assoc()) {
            $pranchas[] = $linha;
        }
    }

    $conexao->close();
    return $pranchas;
}

function criarPrancha($nome, $descricao) {
    $conexao = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);

    if ($conexao->connect_error) {
        return false;
    }

    $sql = "INSERT INTO pranchas (nome, descricao) VALUES (?, ?)";
    $comando = $conexao->prepare($sql);
    $comando->bind_param("ss", $nome, $descricao);

    $resultado = $comando->execute();

    $comando->close();
    $conexao->close();

    return $resultado;
}

function buscarPranchaPorId($id) {
    $conexao = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
    if ($conexao->connect_error) {
        return false;
    }

    $sql = "SELECT id, nome, descricao FROM pranchas WHERE id = ?";
    $comando = $conexao->prepare($sql);
    $comando->bind_param("i", $id);
    $comando->execute();
    $resultado = $comando->get_result();

    $prancha = $resultado->fetch_assoc();

    $comando->close();
    $conexao->close();

    return $prancha;
}

function atualizarPrancha($id, $nome, $descricao) {
    $conexao = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
    if ($conexao->connect_error) {
        return false;
    }

    $sql = "UPDATE pranchas SET nome = ?, descricao = ? WHERE id = ?";
    $comando = $conexao->prepare($sql);
    $comando->bind_param("ssi", $nome, $descricao, $id);

    $resultado = $comando->execute();

    $comando->close();
    $conexao->close();

    return $resultado;
}

function excluirPrancha($id) {
    $conexao = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
    if ($conexao->connect_error) {
        return false;
    }

    $sql = "DELETE FROM pranchas WHERE id = ?";
    $comando = $conexao->prepare($sql);
    $comando->bind_param("i", $id);

    $resultado = $comando->execute();

    $comando->close();
    $conexao->close();

    return $resultado;
}

?>
