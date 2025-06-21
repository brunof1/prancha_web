<?php
require_once '../config/config.php';

function listarGrupos() {
    $conexao = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
    $grupos = [];

    $sql = "SELECT id, nome FROM grupos_cartoes";
    $comando = $conexao->prepare($sql);
    $comando->execute();
    $comando->store_result();
    $comando->bind_result($id, $nome);

    while ($comando->fetch()) {
        $grupos[] = ['id' => $id, 'nome' => $nome];
    }

    $comando->close();
    $conexao->close();

    return $grupos;
}

function listarCartoesPorGrupo($id_grupo) {
    $conexao = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
    $cartoes = [];

    $sql = "SELECT id, titulo, imagem, texto_alternativo FROM cartoes WHERE id_grupo = ? ORDER BY titulo";
    $comando = $conexao->prepare($sql);
    $comando->bind_param("i", $id_grupo);
    $comando->execute();
    $comando->store_result();
    $comando->bind_result($id, $titulo, $imagem, $texto_alt);

    while ($comando->fetch()) {
        $cartoes[] = [
            'id' => $id,
            'titulo' => $titulo,
            'imagem' => $imagem,
            'texto_alternativo' => $texto_alt
        ];
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

    // Primeiro, buscar o nome da imagem para excluir do servidor
    $sql = "SELECT imagem FROM cartoes WHERE id = ?";
    $comando = $conexao->prepare($sql);
    $comando->bind_param("i", $id_cartao);
    $comando->execute();
    $comando->store_result();
    $comando->bind_result($imagem);

    if ($comando->num_rows > 0) {
        $comando->fetch();
        $caminho_imagem = "../imagens/cartoes/" . $imagem;
        if (file_exists($caminho_imagem)) {
            unlink($caminho_imagem);
        }
    }

    $comando->close();

    // Agora deletar o cartão do banco
    $sql = "DELETE FROM cartoes WHERE id = ?";
    $comando = $conexao->prepare($sql);
    $comando->bind_param("i", $id_cartao);
    $resultado = $comando->execute();

    $comando->close();
    $conexao->close();

    return $resultado;
}
?>
