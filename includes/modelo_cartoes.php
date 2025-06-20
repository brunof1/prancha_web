
<?php
require_once '../config/config.php';

function criarCartao($titulo, $texto_alternativo, $imagem, $id_grupo) {
    $conexao = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
    if ($conexao->connect_error) return false;

    $sql = "INSERT INTO cartoes (titulo, texto_alternativo, imagem, id_grupo) VALUES (?, ?, ?, ?)";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("sssi", $titulo, $texto_alternativo, $imagem, $id_grupo);
    $resultado = $stmt->execute();

    $stmt->close();
    $conexao->close();
    return $resultado;
}
?>
