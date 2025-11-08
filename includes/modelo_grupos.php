<?php
require_once '../config/config.php';

function cx_grupos(): mysqli {
    $cx = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
    if ($cx->connect_error) { die("Erro de conexão: " . $cx->connect_error); }
    $cx->set_charset('utf8mb4');
    @$cx->query("SET collation_connection = 'utf8mb4_unicode_ci'");
    return $cx;
}

function listarGrupos() {
    $conexao = cx_grupos();

    $grupos = [];
    $resultado = $conexao->query("SELECT id, nome FROM grupos_cartoes ORDER BY nome");

    if ($resultado && $resultado->num_rows > 0) {
        while ($linha = $resultado->fetch_assoc()) {
            $grupos[] = $linha;
        }
        $resultado->close();
    }

    $conexao->close();
    return $grupos;
}

function criarGrupo($nome_grupo) {
    $conexao = cx_grupos();

    $sql = "INSERT INTO grupos_cartoes (nome) VALUES (?)";
    $comando = $conexao->prepare($sql);
    $comando->bind_param("s", $nome_grupo);
    $resultado = $comando->execute();

    $comando->close();
    $conexao->close();

    return $resultado;
}

function excluirGrupo($id_grupo) {
    $conexao = cx_grupos();

    $sql = "DELETE FROM grupos_cartoes WHERE id = ?";
    $comando = $conexao->prepare($sql);
    $comando->bind_param("i", $id_grupo);
    $resultado = $comando->execute();

    $comando->close();
    $conexao->close();

    return $resultado;
}

function buscarGrupoPorId($id_grupo) {
    $conexao = cx_grupos();

    $sql = "SELECT id, nome FROM grupos_cartoes WHERE id = ?";
    $comando = $conexao->prepare($sql);
    $comando->bind_param("i", $id_grupo);
    $comando->execute();
    $comando->store_result();
    $comando->bind_result($id, $nome);

    if ($comando->num_rows > 0) {
        $comando->fetch();
        $grupo = ['id' => $id, 'nome' => $nome];
    } else {
        $grupo = null;
    }

    $comando->close();
    $conexao->close();

    return $grupo;
}

function atualizarGrupo($id_grupo, $novo_nome) {
    $conexao = cx_grupos();

    $sql = "UPDATE grupos_cartoes SET nome = ? WHERE id = ?";
    $comando = $conexao->prepare($sql);
    $comando->bind_param("si", $novo_nome, $id_grupo);
    $resultado = $comando->execute();

    $comando->close();
    $conexao->close();

    return $resultado;
}
?>
