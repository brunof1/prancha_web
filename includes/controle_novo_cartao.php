
<?php
require_once '../config/config.php';
require_once 'modelo_cartoes.php';
require_once 'modelo_grupos.php';
require_once 'funcoes.php';

require_once __DIR__ . '/acl.php';
require_admin();


$mensagem = "";
$lista_grupos = listarGrupos();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = limparEntrada($_POST['titulo']);
    $texto_alternativo = limparEntrada($_POST['texto_alternativo']);
    $id_grupo = intval($_POST['id_grupo']);

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $nome_arquivo = uniqid() . '_' . basename($_FILES['imagem']['name']);
        $destino = '../imagens/cartoes/' . $nome_arquivo;

        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {
            if (criarCartao($titulo, $texto_alternativo, $nome_arquivo, $id_grupo)) {
                header('Location: ../pages/gerenciar_cartoes.php?sucesso=1');
                exit;
            } else {
                $mensagem = "Erro ao salvar o cartão no banco.";
            }
        } else {
            $mensagem = "Erro ao mover o arquivo da imagem.";
        }
    } else {
        $mensagem = "Erro no upload da imagem.";
    }
}
?>
