<?php
require_once '../config/config.php';

function listarGrupos() {
    $conexao = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
    $grupos = [];
    $resultado = $conexao->query("SELECT id, nome FROM grupos_cartoes");
    if ($resultado && $resultado->num_rows > 0) {
        while ($linha = $resultado->fetch_assoc()) {
            $grupos[] = $linha;
        }
    }
    $conexao->close();
    return $grupos;
}

function listarCartoesPorGrupo($id_grupo) {
    $conexao = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
    $cartoes = [];
    $sql = "SELECT id, titulo, imagem, texto_alternativo FROM cartoes WHERE id_grupo = ?";
    $comando = $conexao->prepare($sql);
    $comando->bind_param("i", $id_grupo);
    $comando->execute();
    $resultado = $comando->get_result();
    while ($linha = $resultado->fetch_assoc()) {
        $cartoes[] = $linha;
    }
    $comando->close();
    $conexao->close();
    return $cartoes;
}

function criarCartao($titulo, $texto_alt, $nome_arquivo_imagem, $id_grupo) {
    $conexao = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
    if ($conexao->connect_error) return false;
    $sql = "INSERT INTO cartoes (titulo, imagem, texto_alternativo, id_grupo) VALUES (?, ?, ?, ?)";
    $comando = $conexao->prepare($sql);
    $comando->bind_param("sssi", $titulo, $nome_arquivo_imagem, $texto_alt, $id_grupo);
    $resultado = $comando->execute();
    $comando->close();
    $conexao->close();
    return $resultado;
}

function excluirCartao($id_cartao) {
    $conexao = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);

    // Primeiro busca o nome do arquivo para deletar a imagem
    $sql = "SELECT imagem FROM cartoes WHERE id = ?";
    $comando = $conexao->prepare($sql);
    $comando->bind_param("i", $id_cartao);
    $comando->execute();
    $resultado = $comando->get_result();
    if ($resultado && $resultado->num_rows > 0) {
        $cartao = $resultado->fetch_assoc();
        if (file_exists("../imagens/cartoes/" . $cartao['imagem'])) {
            unlink("../imagens/cartoes/" . $cartao['imagem']);
        }
    }
    $comando->close();

    // Agora deleta o registro no banco
    $sql = "DELETE FROM cartoes WHERE id = ?";
    $comando = $conexao->prepare($sql);
    $comando->bind_param("i", $id_cartao);
    $resultado = $comando->execute();
    $comando->close();
    $conexao->close();
    return $resultado;
}
?>
