<?php
require_once '../config/config.php';

function listarPranchas() {
    $conexao = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
    $pranchas = [];

    $sql = "SELECT p.id, p.nome, g.nome AS grupo FROM pranchas p 
            INNER JOIN grupos_pranchas g ON p.id_grupo = g.id
            ORDER BY p.nome";

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
    $resultado = $conexao->query("SELECT id, nome FROM grupos_pranchas ORDER BY nome");

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
    $resultado = $conexao->query("SELECT id, titulo, imagem, texto_alternativo FROM cartoes ORDER BY titulo");

    if ($resultado) {
        while ($linha = $resultado->fetch_assoc()) {
            $cartoes[] = $linha;
        }
    }

    $conexao->close();
    return $cartoes;
}

function salvarPrancha($nome, $descricao, $id_grupo, $ordem_cartoes) {
    $conexao = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
    $conexao->begin_transaction();

    try {
        $sql = "INSERT INTO pranchas (nome, descricao, id_grupo) VALUES (?, ?, ?)";
        $comando = $conexao->prepare($sql);
        $comando->bind_param("ssi", $nome, $descricao, $id_grupo);
        $comando->execute();
        $id_prancha = $conexao->insert_id;
        $comando->close();

        foreach ($ordem_cartoes as $index => $id_cartao) {
            $ordem = $index + 1;
            $sql2 = "INSERT INTO pranchas_cartoes (id_prancha, id_cartao, ordem) VALUES (?, ?, ?)";
            $comando2 = $conexao->prepare($sql2);
            $comando2->bind_param("iii", $id_prancha, $id_cartao, $ordem);
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
    $sql = "SELECT id, nome, descricao, id_grupo FROM pranchas WHERE id = ?";
    $comando = $conexao->prepare($sql);
    $comando->bind_param("i", $id_prancha);
    $comando->execute();
    $comando->bind_result($id, $nome, $descricao, $id_grupo);
    if ($comando->fetch()) {
        $prancha = [
            'id' => $id,
            'nome' => $nome,
            'descricao' => $descricao,
            'id_grupo' => $id_grupo
        ];
    } else {
        $prancha = null;
    }
    $comando->close();
    $conexao->close();
    return $prancha;
}

function buscarCartoesDaPrancha($id_prancha) {
    $conexao = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
    $cartoes = [];

    $sql = "SELECT id_cartao FROM pranchas_cartoes WHERE id_prancha = ? ORDER BY ordem ASC";
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

function buscarCartoesPorIds($ids) {
    if (empty($ids)) return [];

    $conexao = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);

    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $tipos = str_repeat('i', count($ids));

    $sql = "SELECT id, titulo, imagem, texto_alternativo FROM cartoes WHERE id IN ($placeholders)";
    $comando = $conexao->prepare($sql);

    $bind_params = array_merge([$tipos], $ids);
    call_user_func_array([$comando, 'bind_param'], refValues($bind_params));

    $comando->execute();
    $comando->store_result();

    $cartoes = [];
    $id = $titulo = $imagem = $texto_alternativo = "";

    $comando->bind_result($id, $titulo, $imagem, $texto_alternativo);

    while ($comando->fetch()) {
        $cartoes[$id] = [
            'id' => $id,
            'titulo' => $titulo,
            'imagem' => $imagem,
            'texto_alternativo' => $texto_alternativo
        ];
    }

    $comando->close();
    $conexao->close();

    // Retorna os resultados na ordem dos IDs passados
    $ordenados = [];
    foreach ($ids as $id_busca) {
        if (isset($cartoes[$id_busca])) {
            $ordenados[] = $cartoes[$id_busca];
        }
    }

    return $ordenados;
}

// Função auxiliar necessária para bind_param dinâmico
function refValues($arr) {
    $refs = [];
    foreach ($arr as $key => $value) {
        $refs[$key] = &$arr[$key];
    }
    return $refs;
}

function atualizarPrancha($id_prancha, $nome, $descricao, $id_grupo, $ordem_cartoes) {
    $conexao = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
    $conexao->begin_transaction();

    try {
        $sql = "UPDATE pranchas SET nome = ?, descricao = ?, id_grupo = ? WHERE id = ?";
        $comando = $conexao->prepare($sql);
        $comando->bind_param("ssii", $nome, $descricao, $id_grupo, $id_prancha);
        $comando->execute();
        $comando->close();

        // Exclui os cartões antigos
        $sql_delete = "DELETE FROM pranchas_cartoes WHERE id_prancha = ?";
        $comando_delete = $conexao->prepare($sql_delete);
        $comando_delete->bind_param("i", $id_prancha);
        $comando_delete->execute();
        $comando_delete->close();

        // Insere novamente com a nova ordem
        foreach ($ordem_cartoes as $index => $id_cartao) {
            $ordem = $index + 1;
            $sql2 = "INSERT INTO pranchas_cartoes (id_prancha, id_cartao, ordem) VALUES (?, ?, ?)";
            $comando2 = $conexao->prepare($sql2);
            $comando2->bind_param("iii", $id_prancha, $id_cartao, $ordem);
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
    $sql_delete_cartoes = "DELETE FROM pranchas_cartoes WHERE id_prancha = ?";
    $comando1 = $conexao->prepare($sql_delete_cartoes);
    $comando1->bind_param("i", $id_prancha);
    $comando1->execute();
    $comando1->close();

    $sql_delete_prancha = "DELETE FROM pranchas WHERE id = ?";
    $comando2 = $conexao->prepare($sql_delete_prancha);
    $comando2->bind_param("i", $id_prancha);
    $resultado = $comando2->execute();
    $comando2->close();

    $conexao->close();
    return $resultado;
}
?>
