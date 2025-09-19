
<?php
require_once __DIR__ . '/acl.php';
require_admin();

require_once 'modelo_grupos.php';
require_once 'funcoes.php';


$mensagem = "";

// Listagem padrão
$lista_grupos = listarGrupos();

// Cadastro de novo grupo
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nome_grupo'])) {
    $nome_grupo = limparEntrada($_POST['nome_grupo']);

    if (!empty($nome_grupo)) {
        if (criarGrupo($nome_grupo)) {
            header('Location: ../pages/gerenciar_grupos_cartoes.php?sucesso=1');
            exit;
        } else {
            $mensagem = "Erro ao criar o grupo.";
        }
    } else {
        $mensagem = "O nome do grupo não pode ser vazio.";
    }
}
?>
