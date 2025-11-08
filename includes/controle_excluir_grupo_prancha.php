<?php
require_once 'modelo_grupos_pranchas.php';

require_once __DIR__ . '/acl.php';
require_admin();


$id_grupo = intval($_GET['id']);

if (excluirGrupoPrancha($id_grupo)) {
    header('Location: ../pages/gerenciar_pranchas.php');
} else {
    echo "Erro ao excluir o grupo.";
}
?>
