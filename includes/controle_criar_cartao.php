<?php
require_once 'modelo_cartoes.php';
require_once __DIR__ . '/acl.php';
require_admin();

$mensagem_erro = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = trim($_POST['titulo']);
    $texto_alt = trim($_POST['texto_alt']);
    $id_grupo = intval($_POST['id_grupo']);

    // Validação
    if (empty($titulo) || $id_grupo <= 0 || !isset($_FILES['imagem'])) {
        $mensagem_erro = "Todos os campos são obrigatórios e o grupo precisa ser selecionado.";
    } else {
        // Upload da imagem
        $diretorio_upload = "../imagens/cartoes/";
        $nome_arquivo = basename($_FILES["imagem"]["name"]);
        $caminho_final = $diretorio_upload . $nome_arquivo;

        if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $caminho_final)) {
            if (criarCartao($titulo, $texto_alt, $nome_arquivo, $id_grupo)) {
                header("Location: ../pages/gerenciar_cartoes.php?sucesso=1");
                exit;
            } else {
                $mensagem_erro = "Erro ao salvar no banco.";
            }
        } else {
            $mensagem_erro = "Falha ao fazer upload da imagem.";
        }
    }
}
?>
