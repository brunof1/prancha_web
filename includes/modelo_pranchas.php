<?php
require_once '../config/config.php';

function listarPranchas() {
    $conexao = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
    $pranchas = [];

    $sql = "SELECT p.id, p.nome, g.nome AS grupo FROM pranchas p 
            INNER JOIN grupos_pranchas g ON p.id_grupo = g.id";

    $resultado = $conexao->query($sql);

    if ($resultado) {
        while ($linha = $resultado->fetch_assoc()) {
            $pranchas[] = [
                'id' => $linha['id'],
                'nome' => $linha['nome'],
                'grupo' => $linha['grupo']
            ];
        }
    }

    $conexao->close();
    return $pranchas;
}

function listarGruposPranchasPranchas() {
    $conexao = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
    $grupos = [];
    $resultado = $conexao->query("SELECT id, nome FROM grupos_pranchas");

    if ($resultado) {
        while ($linha = $resultado->fetch_assoc()) {
            $grupos[] = $linha;
        }
    }

    $conexao->close();
    return $grupos;
}

function listarCartoes() {
    $conexao = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
    $cartoes = [];
    $resultado = $conexao->query("SELECT id, titulo FROM cartoes");

    if ($resultado) {
        while ($linha = $resultado->fetch_assoc()) {
            $cartoes[] = $linha;
        }
    }

    $conexao->close();
    return $cartoes;
}

function salvarPrancha($nome, $descricao, $id_grupo, $cartoes_selecionados) {
    $conexao = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
    $conexao->begin_transaction();

    try {
        $sql = "INSERT INTO pranchas (nome, descricao, id_grupo) VALUES (?, ?, ?)";
        $comando = $conexao->prepare($sql);
        $comando->bind_param("ssi", $nome, $descricao, $id_grupo);
        $comando->execute();
        $id_prancha = $conexao->insert_id;
        $comando->close();

        foreach ($cartoes_selecionados as $id_cartao) {
            $sql2 = "INSERT INTO pranchas_cartoes (id_prancha, id_cartao) VALUES (?, ?)";
            $comando2 = $conexao->prepare($sql2);
            $comando2->bind_param("ii", $id_prancha, $id_cartao);
            $comando2->execute();
            $comando2->close();
        }

        $conexao->commit();
        $conexao->close();
        return true;
    } catch (Exception $e) {
        $conexao->rollback();
        $conexao->close();
        return false;
    }
}

function buscarPranchaPorId($id_prancha) {
    $conexao = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
    $sql = "SELECT nome, descricao, id_grupo FROM pranchas WHERE id = ?";
    $comando = $conexao->prepare($sql);
    $comando->bind_param("i", $id_prancha);
    $comando->execute();
    $comando->bind_result($nome, $descricao, $id_grupo);
    $comando->fetch();
    $comando->close();
    $conexao->close();
    return ['nome' => $nome, 'descricao' => $descricao, 'id_grupo' => $id_grupo];
}

function buscarCartoesDaPrancha($id_prancha) {
    $conexao = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
    $cartoes = [];

    $sql = "SELECT id_cartao FROM pranchas_cartoes WHERE id_prancha = ?";
    $comando = $conexao->prepare($sql);
    $comando->bind_param("i", $id_prancha);
    $comando->execute();
    $comando->bind_result($id_cartao);

    while ($comando->fetch()) {
        $cartoes[] = $id_cartao;
    }

    $comando->close();
    $conexao->close();
    return $cartoes;
}

function atualizarPrancha($id_prancha, $nome, $descricao, $id_grupo, $cartoes_selecionados) {
    $conexao = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
    $conexao->begin_transaction();

    try {
        $sql = "UPDATE pranchas SET nome = ?, descricao = ?, id_grupo = ? WHERE id = ?";
        $comando = $conexao->prepare($sql);
        $comando->bind_param("ssii", $nome, $descricao, $id_grupo, $id_prancha);
        $comando->execute();
        $comando->close();

        $conexao->query("DELETE FROM pranchas_cartoes WHERE id_prancha = $id_prancha");

        foreach ($cartoes_selecionados as $id_cartao) {
            $sql2 = "INSERT INTO pranchas_cartoes (id_prancha, id_cartao) VALUES (?, ?)";
            $comando2 = $conexao->prepare($sql2);
            $comando2->bind_param("ii", $id_prancha, $id_cartao);
            $comando2->execute();
            $comando2->close();
        }

        $conexao->commit();
        $conexao->close();
        return true;
    } catch (Exception $e) {
        $conexao->rollback();
        $conexao->close();
        return false;
    }
}

function excluirPrancha($id_prancha) {
    $conexao = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
    $conexao->query("DELETE FROM pranchas_cartoes WHERE id_prancha = $id_prancha");
    $resultado = $conexao->query("DELETE FROM pranchas WHERE id = $id_prancha");
    $conexao->close();
    return $resultado;
}
?>